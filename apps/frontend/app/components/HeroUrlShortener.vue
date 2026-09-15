<script setup lang="ts">
import type { FormError, FormSubmitEvent } from '@nuxt/ui'
import { isValidUrl } from '~/utils/urlValidation'

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
      message: 'Please enter a valid URL.'
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
        message: 'Something went wrong. Please try again.'
      }
    ])
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <UPageCard
    variant="subtle"
    class="mx-auto max-w-2xl dark:bg-[color-mix(in_oklab,var(--ui-color-neutral-800),var(--ui-bg))]"
  >
    <template #title>
      <div class="flex items-center gap-3">
        <UIcon
          name="i-lucide-link"
          class="text-primary size-5"
        />
        <span class="text-xl">Shorten your URL</span>
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
            placeholder="Paste your long URL here ..."
            class="w-full"
          />
        </UFormField>

        <UButton
          :loading="isLoading"
          type="submit"
          size="xl"
          label="Shorten"
          class="shrink-0 w-30 justify-center"
        />
      </div>
    </UForm>

    <UCard
      v-if="hasResult"
      title="Your short code"
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
          aria-label="Copy to clipboard"
          class="-translate-y-1"
          @click="copy(shortUrl)"
        />
      </div>
    </UCard>
  </UPageCard>
</template>
