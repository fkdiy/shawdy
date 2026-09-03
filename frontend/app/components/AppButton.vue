<script setup lang="ts">
const _ALLOWED_VARIANTS = ["primary", "secondary"] as const;

export type ButtonVariant = (typeof _ALLOWED_VARIANTS)[number];

const {
  href = "",
  variant = "primary",
  loading = false,
} = defineProps<{
  href?: string;
  variant?: ButtonVariant;
  loading?: boolean;
}>();
</script>

<template>
  <component
    :is="href ? 'a' : 'button'"
    :href="href || undefined"
    :disabled="!href ? loading : undefined"
    type="button"
    class="inline-flex h-fit w-full justify-center self-start rounded-md py-3 font-bold transition sm:w-auto sm:self-end"
    :class="{
      'bg-primary text-text-inverse hover:bg-primary-hover active:bg-primary-hover':
        variant === 'primary',
      'bg-surface text-text-primary outline-1 outline-border hover:bg-background active:bg-background':
        variant === 'secondary',
      'px-8': !loading,
      'px-5': loading,
    }"
  >
    <span class="inline-flex items-center justify-center gap-2">
      <span
        v-if="loading"
        class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
      />
      <slot />
    </span>
  </component>
</template>
