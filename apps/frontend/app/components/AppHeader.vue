<script setup lang="ts">
const localePath = useLocalePath()
</script>

<template>
  <UHeader
    :toggle="false"
  >
    <template #left>
      <NuxtLink
        :to="localePath('/')"
        class="focus-visible:outline-3 outline-primary/25 rounded-md p-1 -ms-1"
      >
        <AppLogo class="w-auto h-6 shrink-0" />
      </NuxtLink>
    </template>

    <template #right>
      <UColorModeButton />

      <UButton
        to="https://github.com/fkdiy/shawdy"
        target="_blank"
        icon="i-simple-icons-github"
        aria-label="GitHub"
        color="neutral"
        variant="ghost"
      />
    </template>
  </UHeader>
</template>
