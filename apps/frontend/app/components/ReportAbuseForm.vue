<script setup lang="ts">
import type { FormSubmitEvent } from '@nuxt/ui'
import { z } from 'zod'
import { useAbuseReportsApi } from '~/composables/api/useAbuseReportsApi'
import { isValidShawdyShortUrl } from '~/utils/urlValidation'
import { getApiValidationErrors } from '~/utils/apiValidation'

const { page } = await usePageContent()

const { locale, t } = useI18n()

// Form validation
const schema = z.object({
  shortUrl: z
    .string()
    .min(1, t('errors.validation.required'))
    .refine(
      value => isValidShawdyShortUrl(value, requestUrl.hostname),
      t('errors.validation.shawdyUrl')
    ),

  email: z
    .string()
    .min(1, t('errors.validation.required'))
    .pipe(
      z.email(t('errors.validation.email'))
    ),

  message: z.string()
})

type Schema = z.output<typeof schema>

// Form and request state
const state = reactive<Schema>({
  shortUrl: '',
  email: '',
  message: ''
})

const form = useTemplateRef('form')
const isLoading = ref(false)
const toast = useToast()

// Dependencies
const { createAbuseReport } = useAbuseReportsApi()
const requestUrl = useRequestURL()

// Form submission
async function onSubmit(event: FormSubmitEvent<Schema>) {
  isLoading.value = true
  form.value?.clear()

  try {
    await createAbuseReport({
      shortUrl: event.data.shortUrl.trim(),
      email: event.data.email.trim(),
      message: event.data.message.trim(),
      locale: locale.value
    })

    state.shortUrl = ''
    state.email = ''
    state.message = ''

    form.value?.clear()

    toast.add({
      title: t('common.thankYou'),
      description: t('common.messageSubmitted'),
      icon: 'lucide-circle-check',
      color: 'primary'
    })
  } catch (error) {
    const validationErrors = getApiValidationErrors(error, t)

    console.log(validationErrors)

    if (validationErrors.length > 0) {
      form.value?.setErrors(validationErrors)
      return
    }

    form.value?.setErrors([
      {
        name: 'shortUrl',
        message: t('errors.generic')
      }
    ])
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="w-full">
    <UPageCard
      v-if="page.reportAbuseForm"
      class="mt-2 mb-3"
      variant="subtle"
    >
      <UForm
        ref="form"
        class="space-y-4"
        :schema="schema"
        :state="state"
        :validate-on="[]"
        @submit="onSubmit"
      >
        <UFormField
          name="shortUrl"
          size="xl"
          :label="page.reportAbuseForm.shortUrlLabel"
          :ui="{
            error: 'text-sm'
          }"
          required
        >
          <UInput
            id="shortUrl"
            v-model="state.shortUrl"
            data-testid="short-url-input"
            class="w-full"
            :placeholder="page.reportAbuseForm.shortUrlPlaceholder"
          />
        </UFormField>

        <UFormField
          name="email"
          size="xl"
          :label="page.reportAbuseForm.contactEmailLabel"
          :ui="{
            error: 'text-sm'
          }"
          required
        >
          <UInput
            id="email"
            v-model="state.email"
            data-testid="email-input"
            type="email"
            class="w-full"
            :placeholder="page.reportAbuseForm.contactEmailPlaceholder"
          />
        </UFormField>

        <UFormField
          name="message"
          size="xl"
          :label="page.reportAbuseForm.descriptionLabel"
        >
          <UTextarea
            id="message"
            v-model="state.message"
            data-testid="message-textarea"
            class="w-full"
            :rows="5"
            :placeholder="page.reportAbuseForm.descriptionPlaceholder"
          />
        </UFormField>

        <p class="text-sm text-muted">
          {{ page.reportAbuseForm.requiredFieldsInfo }}
        </p>

        <UButton
          data-testid="abuse-report-submit"
          type="submit"
          size="xl"
          :loading="isLoading"
        >
          {{ page.reportAbuseForm.submitLabel }}
        </UButton>
      </UForm>
    </UPageCard>
  </div>
</template>
