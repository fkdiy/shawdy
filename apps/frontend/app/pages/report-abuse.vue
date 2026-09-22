<script setup lang="ts">
const { page } = await usePageContent()
console.log(page)
</script>

<template>
  <AppPageHeader />

  <div class="px-4 sm:px-6 lg:px-8">
    <UPage
      class="w-full max-w-4xl mx-auto"
      :ui="{
        center: 'lg:col-span-5 pt-4',
        right: 'lg:col-span-5 mt-18'
      }"
    >
      <template #right>
        <ReportAbuseForm />
      </template>

      <ContentRenderer
        :value="page"
      />
    </UPage>
    <UPageCard
      v-if="page.reportAbuseForm"
      class="w-full max-w-4xl mx-auto mt-12 mb-24 text-muted"
      variant="outline"
      icon="i-lucide-info"
      :title="page.reportAbuseForm.personalDiscretionInfoTitle"
    >
      {{ page.reportAbuseForm.personalDiscretionInfoText }}
    </UPageCard>
  </div>
</template>
