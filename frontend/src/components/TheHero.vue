<script setup lang="ts">
import AppButton, { type ButtonVariant } from './AppButton.vue'

interface HeroButton {
  text: string
  href: string
  variant: ButtonVariant
}

defineProps<{
  title: string
  titleAccent: string
  subtitle: string
  primaryButton?: HeroButton
  secondaryButton?: HeroButton
}>()
</script>

<template>
  <h1 class="text-center text-5xl font-bold tracking-tight text-text-primary lg:text-left">
    {{ title }}
    <span class="mt-2 block text-primary">
      {{ titleAccent }}
    </span>
  </h1>

  <p class="mt-9 text-muted text-center text-lg leading-8 lg:text-left">
    {{ subtitle }}
  </p>

  <div
    v-if="primaryButton || secondaryButton"
    class="mt-10 flex flex-col flex-wrap justify-center gap-4 sm:flex-row lg:justify-start"
  >
    <AppButton v-if="primaryButton" :href="primaryButton.href" :variant="primaryButton.variant">
      {{ primaryButton.text }}
    </AppButton>

    <AppButton
      v-if="secondaryButton"
      :href="secondaryButton.href"
      :variant="secondaryButton.variant"
    >
      {{ secondaryButton.text }}
    </AppButton>
  </div>
</template>
