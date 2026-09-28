<script setup lang="ts">
import { scrollMotion, staggerMotion } from '~/utils/motion'

const { page } = await usePageContent()
</script>

<template>
  <UPageSection
    id="preview"
    :ui="{
      root: 'pb-6 sm:pb-12 scroll-mt-(--ui-header-height)',
      container: 'max-w-5xl',
      headline: 'font-mono font-medium text-xs text-primary uppercase tracking-[0.12em] text-center',
      title: 'max-w-xl mx-auto',
      description: 'max-w-xl mx-auto text-dimmed'
    }"
  >
    <template #headline>
      <Motion
        v-if="page.landingFeaturePreview"
        as="span"
        v-bind="scrollMotion()"
        class="inline-block"
      >
        {{ page.landingFeaturePreview.headline }}
      </Motion>
    </template>

    <template #title>
      <Motion
        v-if="page.landingFeaturePreview"
        as="span"
        v-bind="scrollMotion(0.1)"
        class="inline-block"
      >
        {{ page.landingFeaturePreview.title }}
      </Motion>
    </template>

    <template #description>
      <Motion
        v-if="page.landingFeaturePreview"
        as="span"
        v-bind="scrollMotion(0.2)"
        class="inline-block"
      >
        {{ page.landingFeaturePreview.description }}
      </Motion>
    </template>

    <UPageGrid
      v-if="page.landingFeaturePreview"
    >
      <Motion
        v-for="(feature, index) in page.landingFeaturePreview.items"
        :key="feature.title"
        v-bind="staggerMotion(index)"
      >
        <UPageCard
          spotlight
          spotlight-color="primary"
          :icon="feature.icon"
          :title="feature.title"
          :description="feature.description"
        />
      </Motion>
    </UPageGrid>
  </UPageSection>
</template>
