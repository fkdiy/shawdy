<script setup lang="ts">
const ALLOWED_VARIANTS = ['default', 'contrast'] as const

type CardVariant = (typeof ALLOWED_VARIANTS)[number]

const props = defineProps<{
  variant?: CardVariant
}>()

const { variant = 'default' } = props
</script>

<template>
  <div
    class="rounded-lg border border-border p-8 mb-6"
    :class="{
      'bg-surface': variant === 'default',
      'dark:bg-background': variant === 'contrast',
    }"
  >
    <slot />
  </div>
</template>
