<script setup lang="ts">
import { enterMotion } from '~/utils/motion'

const { page } = await usePageContent()

const heroTitle = computed(() => {
  const [primary = '', ...secondaryParts] = (page?.value?.hero?.title ?? '').split('\n')

  return {
    primary,
    secondary: secondaryParts.join(' ').trim()
  }
})
</script>

<template>
  <UPageHero
    v-if="page.hero"
    :ui="{
      headline: 'font-mono font-medium text-xs text-primary uppercase tracking-[0.12em] text-center'
    }"
  >
    <template #top>
      <LazyBackgroundStars
        class="hidden dark:block"
      />
    </template>

    <template #headline>
      <Motion v-bind="enterMotion(0.2)">
        {{ page.hero.headline }}
      </Motion>
    </template>

    <template #title>
      <Motion
        as="span"
        v-bind="enterMotion(0.35)"
        class="inline-block leading-14 sm:leading-20"
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
        as="span"
        v-bind="enterMotion(0.5)"
        class="inline-block"
      >
        {{ page.hero.description }}
      </Motion>
    </template>

    <template #links>
      <Motion
        class="flex flex-wrap justify-center gap-6"
        v-bind="enterMotion(0.65)"
      >
        <UButton
          v-for="link in page.hero.links"
          :key="link.label"
          v-bind="link"
        />
      </Motion>
    </template>

    <Motion
      as="span"
      v-bind="enterMotion(0.8)"
      class="inline-block"
    >
      <HeroUrlShortener />
    </Motion>
  </UPageHero>
</template>
