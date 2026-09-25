<script setup lang="ts">
import type { NuxtError } from '#app'

const props = defineProps<{
  error: NuxtError
}>()

const { t } = useI18n()

const description = computed(() => {
  switch (props.error.status) {
    case 404:
      return t('errors.http.404')

    case 500:
      return t('errors.http.500')

    case 502:
      return t('errors.http.502')

    case 503:
      return t('errors.http.502')

    default:
      return t('errors.http.generic')
  }
})
</script>

<template>
  <AppHeader />
  <UPageHero
    :ui="{
      headline: 'font-mono font-medium text-xs text-primary uppercase tracking-[0.12em] text-center',
      title: 'text-7xl sm:text-9xl'
    }"
  >
    <template #headline>
      {{ t('errors.http.headline') }}
    </template>

    <template #title>
      Error
      <span
        class="text-primary"
      >
        {{ props.error.status }}
      </span>
    </template>

    <template #description>
      {{ description }}
    </template>

    <template #links>
      <UButton
        size="xl"
        :label="t('errors.http.returnHome')"
        to="/"
      />
    </template>
  </UPageHero>
  <AppFooter />
</template>
