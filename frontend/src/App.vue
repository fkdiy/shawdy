<script setup lang="ts">
import TheHeader from './components/TheHeader.vue'
import TheFooter from './components/TheFooter.vue'
import TheHero from './components/TheHero.vue'
import UrlShortener from './features/url-shortener/UrlShortener.vue';
</script>

<template>
    <div class="flex min-h-screen flex-col bg-background text-text antialiased">

        <!-- Header -->
        <TheHeader />

        <!-- Main -->
        <main class="w-full px-4 sm:px-6 lg:px-8">
            <div class="mx-auto w-full max-w-7xl py-20">
                <div class="grid gap-x-32 lg:grid-cols-2 lg:items-start">

                    <!-- Hero -->
                    <section class="mb-28 lg:mb-0">
                      <TheHero
                          title="I built yet another"
                          title-accent="URL shortener"
                          subtitle="Shawdy is my learning project for exploring Git, Docker, CI/CD, Symfony, API Platform, Vue.js and modern web development with a hint of Python."
                          :primary-button="{
                              text: 'See how it works',
                              href: '/about',
                              variant: 'primary'
                          }"
                          :secondary-button="{
                              text: 'View on GitHub',
                              href: 'https://github.com/fkdiy/shawdy',
                              variant: 'secondary'
                          }"
                      />
                    </section>

                    <!-- URL Shortener -->
                    <aside class="mb-8 lg:mb-0">
                      <UrlShortener />
                    </aside>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <TheFooter />
    </div>
</template>

<style scoped></style>
