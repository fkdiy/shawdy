<script setup lang="ts">
const { page } = await usePageContent()
</script>

<template>
  <div v-if="page">
    <AppHero />
  </div>
</template>
