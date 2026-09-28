<script setup lang="ts">
import { scrollMotion, staggerMotion } from '~/utils/motion'

const { page } = await usePageContent()
</script>

<template>
  <UPageSection
    id="ecosystem"
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
        v-if="page.landingEcosystem"
        as="span"
        v-bind="scrollMotion()"
        class="inline-block"
      >
        {{ page.landingEcosystem.headline }}
      </Motion>
    </template>

    <template #title>
      <Motion
        v-if="page.landingEcosystem"
        as="span"
        v-bind="scrollMotion(0.1)"
        class="inline-block"
      >
        {{ page.landingEcosystem.title }}
      </Motion>
    </template>

    <template #description>
      <Motion
        v-if="page.landingEcosystem"
        as="span"
        v-bind="scrollMotion(0.2)"
        class="inline-block"
      >
        {{ page.landingEcosystem.description }}
      </Motion>
    </template>

    <div
      v-if="page.landingEcosystem"
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
    >
      <Motion
        v-for="(service, index) in page.landingEcosystem.items"
        :key="service.title"
        v-bind="staggerMotion(index)"
      >
        <UPageFeature
          :icon="service.icon"
          :title="service.title"
          :description="service.description"
        />
      </Motion>
    </div>
  </UPageSection>
</template>
