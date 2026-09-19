<script setup lang="ts">
import type { FormError, FormSubmitEvent } from '@nuxt/ui'
import { isValidUrl } from '~/utils/urlValidation'

const { page } = await usePageContent()

type Schema = {
  url: string
}

// Form and request state
const state = reactive<Schema>({
  url: ''
})

const form = useTemplateRef('form')
const shortCode = ref('')
const isLoading = ref(false)
const hasResult = ref(false)

// Dependencies
const { createShortUrl } = useShortUrlsApi()
const { copy, copied } = useClipboard()
const requestUrl = useRequestURL()

// Derived state
const shortUrl = computed(() => {
  if (!shortCode.value) {
    return ''
  }

  return new URL(
    `/${encodeURIComponent(shortCode.value)}`,
    requestUrl.origin
  ).toString()
})

// Form validation
function validate(state: Partial<Schema>): FormError[] {
  if (!state.url || !isValidUrl(state.url)) {
    return [{
      name: 'url',
      message: page.value?.hero?.urlShortener.validationError ?? ''
    }]
  }

  return []
}

// Form submission
async function onSubmit(event: FormSubmitEvent<Schema>) {
  isLoading.value = true
  form.value?.clear()

  try {
    const response = await createShortUrl(event.data.url.trim())

    shortCode.value = response.shortCode
    hasResult.value = true
  } catch {
    shortCode.value = ''
    hasResult.value = false

    form.value?.setErrors([
      {
        name: 'url',
        message: page.value?.hero?.urlShortener.generalError ?? ''
      }
    ])
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <UPageCard
    v-if="page.hero?.urlShortener"
    variant="subtle"
    class="mx-auto max-w-2xl dark:bg-[color-mix(in_oklab,var(--ui-color-neutral-800),var(--ui-bg))]"
  >
    <template #title>
      <div class="flex items-center gap-3">
        <UIcon
          :name="page.hero.urlShortener.icon"
          class="text-primary size-4.5"
        />
        <span class="text-xl">{{ page.hero.urlShortener.cta }}</span>
      </div>
    </template>

    <UForm
      ref="form"
      :validate="validate"
      :validate-on="[]"
      :state="state"
      @submit="onSubmit"
    >
      <div class="flex w-full items-start gap-x-6">
        <UFormField
          name="url"
          orientation="vertical"
          class="min-w-0 flex-1"
        >
          <UInput
            id="targetUrl"
            v-model="state.url"
            size="xl"
            :placeholder="page.hero.urlShortener.placeholder"
            class="w-full"
          />
        </UFormField>

        <UButton
          :loading="isLoading"
          type="submit"
          size="xl"
          :label="page.hero.urlShortener.buttonLabel"
          class="shrink-0 w-30 justify-center"
        />
      </div>
    </UForm>

    <UCard
      v-if="hasResult"
      :title="page.hero.urlShortener.resultTitle"
      class="w-full"
      :ui="{
        root: 'divide-none',
        header: 'py-2 px-3 sm:px-3',
        body: 'p-2 sm:p-2 sm:py-2 px-3 sm:px-3'
      }"
    >
      <div class="flex items-center">
        <span
          class="w-full font-mono"
          data-testid="short-url-result"
        >
          {{ shortUrl }}
        </span>
        <UButton
          size="xs"
          :icon="copied ? 'i-lucide-copy-check' : 'i-lucide-copy'"
          :color="copied ? 'primary' : 'neutral'"
          variant="link"
          :aria-label="page.hero.urlShortener.copyToClipboardAria"
          class="-translate-y-1"
          @click="copy(shortUrl)"
        />
      </div>
    </UCard>
  </UPageCard>
</template>
