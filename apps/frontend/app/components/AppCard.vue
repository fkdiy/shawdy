<script setup lang="ts">
const _ALLOWED_VARIANTS = ["default", "contrast"] as const;

type CardVariant = (typeof _ALLOWED_VARIANTS)[number];

const { variant = "default" } = defineProps<{
  variant?: CardVariant;
}>();
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
