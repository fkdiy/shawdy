<script setup lang="ts">
const { page } = await usePageContent()
</script>

<template>
  <PageHeader />

  <div class="px-4 sm:px-6 lg:px-8 mt-16">
    <UPage
      class="w-full max-w-4xl mx-auto mb-16"
      :ui="{
        center: 'lg:col-span-5',
        right: 'lg:col-span-5'
      }"
    >
      <template #right>
        <ReportAbuseForm />
      </template>

      <ContentRenderer
        class="[&>h2]:mt-14 lg:[&>h2]:mt-0 [&>p:last-child]:mb-0"
        :value="page"
      />
    </UPage>
    <UPageCard
      v-if="page.reportAbuseForm"
      class="w-full max-w-4xl mx-auto mb-24 text-muted"
      variant="outline"
      icon="i-lucide-info"
      :title="page.reportAbuseForm.personalDiscretionInfoTitle"
    >
      {{ page.reportAbuseForm.personalDiscretionInfoText }}
    </UPageCard>
  </div>
</template>
