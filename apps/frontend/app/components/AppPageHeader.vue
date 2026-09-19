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
      <Motion
        as="span"
        v-bind="enterMotion(0.2)"
        class="inline-block"
      >
        {{ page.pageHeader.headline }}
      </Motion>
    </template>

    <template #title>
      <Motion
        as="span"
        v-bind="enterMotion(0.35)"
        class="inline-block"
      >
        {{ page.pageHeader.title }}
      </Motion>
    </template>

    <template #description>
      <Motion
        as="span"
        v-bind="enterMotion(0.5)"
        class="inline-block"
      >
        {{ page.pageHeader.description }}
      </Motion>
    </template>
  </UPageHeader>
</template>
