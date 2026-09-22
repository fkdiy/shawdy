<script setup lang="ts">
import type { FormError, FormSubmitEvent } from '@nuxt/ui'

const { page } = await usePageContent()

type Schema = {
  url: string
}

// Form and request state
const state = reactive<Schema>({
  url: ''
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
const toast = useToast()
async function onSubmit(event: FormSubmitEvent<Schema>) {
  toast.add({ title: 'Success', description: 'The form has been submitted.', color: 'success' })
  console.log(event.data)
}
</script>

<template>
  <div class="w-full">
    <UPageCard
      v-if="page.reportAbuseForm"
      variant="subtle"
    >
      <UForm
        ref="form"
        class="space-y-4"
        :validate="validate"
        :validate-on="[]"
        :state="state"
        @submit="onSubmit"
      >
        <UFormField
          size="xl"
          :label="page.reportAbuseForm.shortUrlLabel"
          required
        >
          <UInput
            class="w-full"
            :placeholder="page.reportAbuseForm.shortUrlPlaceholder"
          />
        </UFormField>

        <UFormField
          size="xl"
          :label="page.reportAbuseForm.contactEmailLabel"
          required
        >
          <UInput
            class="w-full"
            :placeholder="page.reportAbuseForm.contactEmailPlaceholder"
          />
        </UFormField>

        <UFormField
          size="xl"
          :label="page.reportAbuseForm.descriptionLabel"
        >
          <UTextarea
            class="w-full"
            :rows="5"
            :placeholder="page.reportAbuseForm.descriptionPlaceholder"
          />
        </UFormField>

        <p
          class="text-sm text-muted"
        >
          {{ page.reportAbuseForm.requiredFieldsInfo }}
        </p>

        <UButton
          type="submit"
          size="xl"
        >
          {{ page.reportAbuseForm.submitLabel }}
        </UButton>
      </UForm>
    </UPageCard>
  </div>
</template>
