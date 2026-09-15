<script setup lang="ts">
import { scrollMotion } from '~/utils/motion'

const { page } = await usePageContent()
</script>

<template>
  <UPageSection
    v-if="page"
    id="ecosystem"
    :ui="{
      root: 'py-24 sm:py-32 scroll-mt-(--ui-header-height)',
      container: 'max-w-5xl',
      headline: 'font-mono font-medium text-xs text-primary uppercase tracking-[0.12em] text-center',
      title: 'max-w-xl mx-auto',
      description: 'max-w-xl mx-auto text-dimmed'
    }"
  >
    <template #headline>
      <Motion
        as="span"
        v-bind="scrollMotion()"
        class="inline-block"
      >
        {{ page.features.headline }}
      </Motion>
    </template>

    <template #title>
      <Motion
        as="span"
        v-bind="scrollMotion(0.1)"
        class="inline-block"
      >
        {{ page.features.title }}
      </Motion>
    </template>

    <template #description>
      <Motion
        as="span"
        v-bind="scrollMotion(0.2)"
        class="inline-block"
      >
        {{ page.features.description }}
      </Motion>
    </template>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <Motion
        v-for="(feature, index) in page.features.items"
        :key="feature.title"
        v-bind="staggerMotion(index)"
      >
        <UPageFeature
          :icon="feature.icon"
          :title="feature.title"
          :description="feature.description"
        />
      </Motion>
    </div>
  </UPageSection>
</template>
