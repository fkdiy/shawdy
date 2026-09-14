<script setup lang="ts">
import { enterMotion, scrollMotion, staggerMotion } from '~/utils/motion'

const { page } = await usePageContent()

console.log(page)

const heroTitle = computed(() => {
  const [primary = '', ...secondaryParts] = (page.value?.title ?? '').split('\n')

  return {
    primary,
    secondary: secondaryParts.join(' ').trim()
  }
})
</script>

<template>
  <UPageHero
    :ui="{
      container: 'gap-y-10 sm:gap-y-10 lg:gap-y-10 py-24 sm:py-24 lg:py-24'
    }"
  >
    <template #top>
      <LazyBackgroundStars
        class="hidden dark:block"
      />
    </template>

    <template #title>
      <Motion
        as="span"
        v-bind="enterMotion(0.35)"
        class="inline-block text-6xl leading-18"
      >
        {{ heroTitle.primary }}
        <br
          v-if="heroTitle.secondary"
        >
        <span
          v-if="heroTitle.secondary"
          class="text-primary"
        >
          {{ heroTitle.secondary }}
        </span>
      </Motion>
    </template>

    <template #description>
      <Motion
        v-if="page"
        as="span"
        v-bind="enterMotion(0.5)"
        class="inline-block"
      >
        {{ page.description }}
      </Motion>
    </template>

    <Motion
      as="span"
      v-bind="enterMotion(0.65)"
      class="inline-block"
    >
      <UrlShortener />
    </Motion>
  </UPageHero>
</template>
