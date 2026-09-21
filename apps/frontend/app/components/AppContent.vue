<script setup lang="ts">
const { page } = await usePageContent()

const { t } = useI18n()

const topLevelLinks = computed(() => {
  if (!page.value?.body?.toc?.links) return []

  return page.value.body.toc.links.map(link => ({
    ...link,
    children: []
  }))
})
</script>

<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <UPage
      class="w-full max-w-4xl mx-auto mb-24"
      :ui="{
        center: 'lg:col-span-7 pt-4',
        right: 'lg:col-span-3 pt-9'
      }"
    >
      <ContentRenderer
        :value="page"
      />

      <template #right>
        <UPageAside>
          <UContentToc
            :ui="{
              linkText: 'overflow-auto whitespace-normal'
            }"
            :links="topLevelLinks"
            :title="t('content.toc')"
            highlight-color="primary"
          />
        </UPageAside>
      </template>
    </UPage>
  </div>
</template>
