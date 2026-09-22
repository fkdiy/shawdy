<script setup lang="ts">
import { de, en } from '@nuxt/ui/locale'

const { locale, setLocale, t } = useI18n()

async function onLocaleChange(value: string): Promise<void> {
  if (value !== 'de' && value !== 'en') {
    return
  }

  await setLocale(value)
}

const localePath = useLocalePath()

const links = computed(() => [
  { label: t('navigation.legal'), to: localePath('/legal') },
  { label: t('navigation.privacy'), to: localePath('/privacy') },
  { label: t('navigation.reportAbuse'), to: localePath('/report-abuse') }
])
</script>

<template>
  <USeparator />

  <UFooter
    :ui="{
      container: 'lg:py-8',
      right: 'gap-x-0 flex-wrap'
    }
    "
  >
    <template #right>
      <UButton
        v-for="link in links"
        :key="link.label"
        :label="link.label"
        :to="link.to"
        color="neutral"
        variant="link"
        size="sm"
      />

      <ULocaleSelect
        :model-value="locale"
        :locales="[de, en]"
        :ui="{
          value: 'text-xs',
          leading: 'hidden',
          base: 'ml-2 ps-2.5',
          item: '[&>span:first-child]:hidden'
        }"
        class="w-28"
        variant="soft"
        size="xs"
        @update:model-value="onLocaleChange"
      />
    </template>

    <template #left>
      <p class="text-sm text-muted">
        Made with code and coffee by Fabian König · © {{ new Date().getFullYear() }}
      </p>
    </template>
  </UFooter>
</template>
