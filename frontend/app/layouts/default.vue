<template>
  <div class="flex min-h-screen flex-col bg-background text-text antialiased">
    <TheHeader />

    <main class="w-full px-4 sm:px-6 lg:px-8">
      <slot />
    </main>

    <TheFooter />
  </div>
</template>
