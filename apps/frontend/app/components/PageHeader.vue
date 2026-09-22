<script setup lang="ts">
const { page } = await usePageContent()
</script>

<template>
  <UPageHeader
    v-if="page.pageHeader"
    :ui="{
      root: 'px-4 sm:px-6 lg:px-8 py-16',
      container: 'mx-auto w-full max-w-4xl',
      headline: 'mx-auto w-full max-w-4xl font-mono font-medium text-xs text-primary uppercase tracking-[0.12em]',
      title: 'text-3xl sm:text-4xl lg:text-5xl',
      description: 'text-base sm:text-lg text-dimmed'
    }"
  >
    <template #top />

    <template #headline>
      <LazyBackgroundStars
        class="hidden dark:block"
      />
      {{ page.pageHeader.headline }}
    </template>

    <template #title>
      {{ page.pageHeader.title }}
    </template>

    <template #description>
      {{ page.pageHeader.description }}
    </template>
  </UPageHeader>
</template>
