<script setup lang="ts">
const { page } = await usePageContent()

const proseUi = {
  prose: {
    h2: {
      base: 'text-2xl sm:text-3xl text-pretty'
    },
    h3: {
      base: 'text-xl text-pretty -mb-1'
    },
    h4: {
      base: 'text-base text-pretty -mb-2'
    },
    p: {
      base: 'text-[15px] leading-6 text-muted text-pretty my-0 mt-3 mb-5'
    },
    li: {
      base: 'text-[15px] leading-6 text-muted text-pretty'
    }
  }
}
</script>

<template>
  <AppPageHeader />

  <UPageBody
    class="px-4 sm:px-6 lg:px-8 mt-2"
  >
    <UContainer
      class="mx-auto w-full max-w-4xl px-0 sm:px-0 lg:px-0"
    >
      <Motion
        as="span"
        v-bind="enterMotion(0.65)"
        class="inline-block"
      >
        <UTheme
          :ui="proseUi"
        >
          <ContentRenderer
            :value="page"
          />
        </UTheme>
      </Motion>
    </UContainer>
  </UPageBody>
</template>
