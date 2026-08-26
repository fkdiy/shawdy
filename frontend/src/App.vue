<script setup lang="ts">
import { ref } from 'vue'
import { createShortUrl } from './api/shortUrls'

const targetUrl = ref('')
const shortCode = ref('')
const isLoading = ref(false)
const error = ref('')

async function shortenUrl() {
    if (!targetUrl.value.trim()) {
        return
    }

    isLoading.value = true
    error.value = ''
    shortCode.value = ''

    try {
        const response = await createShortUrl(targetUrl.value.trim())
        shortCode.value = response.shortCode
    } catch {
        error.value = 'Something went wrong. Please try again.'
    } finally {
        isLoading.value = false
    }
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-background text-text antialiased">

        <!-- Header -->
        <header class="grid h-20 items-center border-b border-border dark:border-border/60 bg-surface dark:bg-background px-4 sm:px-6 lg:px-8">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between">
                <img src="/logo-shawdy.svg" alt="Shawdy" class="h-7">

                <nav class="flex gap-4">
                    <a href="#" class="transition-colors hover:text-primary">
                        Home
                    </a>
                    <a href="#" class="transition-colors hover:text-primary">
                        About
                    </a>
                    <a href="#" class="transition-colors hover:text-primary">
                        Contact
                    </a>
                </nav>
            </div>
        </header>

        <!-- Main -->
        <main class="w-full px-4 sm:px-6 lg:px-8">
            <div class="mx-auto w-full max-w-7xl py-20">
                <div class="grid gap-x-20 lg:grid-cols-2 lg:items-start">

                    <!-- Intro -->
                    <section class="mb-14 lg:mb-0">
                        <h1 class="text-center text-5xl font-bold tracking-tight text-text-primary lg:text-left">
                            I built yet another
                            <span class="mt-2 block text-primary">
                                URL shortener
                            </span>
                        </h1>

                        <p class="mt-8 text-muted text-center text-lg leading-8 lg:text-left">
                            Shawdy is my learning project for exploring Git, Docker,
                            CI/CD, Symfony, Vue.js and modern web development.
                        </p>

                        <div class="mt-10 mb-12 flex flex-col flex-wrap justify-center gap-4 sm:flex-row lg:justify-start">
                            <button
                                type="button"
                                class="rounded-md bg-primary px-8 py-3 font-bold text-text-inverse transition-all hover:bg-primary-hover"
                            >
                                Click me to do something
                            </button>

                            <button
                                type="button"
                                class="rounded-md bg-surface px-8 py-3 font-bold text-text-primary outline-1 outline-border transition-all hover:bg-background"
                            >
                                Or click me
                            </button>
                        </div>
                    </section>

                    <!-- URL Shortener -->
                    <aside class="mb-8 lg:mb-0">
                        <div class="rounded-lg border border-border bg-surface p-8">

                            <div class="mb-6 flex items-center gap-3">
                                <img src="/icon-link.svg" alt="" class="h-6">

                                <h2 class="text-xl font-semibold leading-none text-text-primary">
                                    Shorten your URL
                                </h2>
                            </div>

                            <div class="flex w-full gap-4">
                                <input
                                    v-model="targetUrl"
                                    type="url"
                                    placeholder="Paste your long URL here..."
                                    class="min-w-0 flex-1 rounded-md border border-border bg-input px-4 py-3 text-text-primary placeholder:text-input-placeholder focus:outline-none"
                                    @keyup.enter="shortenUrl"
                                >

                                <button
                                    type="button"
                                    class="shrink-0 rounded-md bg-primary px-8 py-3 font-bold text-text-inverse transition-all hover:bg-primary-hover"
                                    @click="shortenUrl"
                                >
                                    Shorten
                                </button>
                            </div>
                        </div>
                        <div v-if="shortCode" class="rounded-lg bg-surface dark:bg-background border border-border p-8 mt-6">
                            <p class="text-m text-muted mb-4">
                                Your short URL:
                            </p>
                            <a
                                :href="`http://localhost:8080/${shortCode}`"
                                target="_blank"
                                class="text-primary text-xl font-semibold hover:text-text"
                            >
                                http://localhost:8080/{{ shortCode }}
                            </a>
                        </div>
                    </aside>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="h-20 mt-auto grid items-center border-t border-border dark:border-border/60 bg-background px-4 sm:px-6 lg:px-8">
            <div class="mx-auto flex w-full max-w-7xl flex-col items-center justify-between gap-4 py-6 sm:flex-row">
                <p class="text-sm opacity-80">
                    &copy; 2026 Shawdy. All rights reserved.
                </p>

                <nav class="flex gap-4 text-sm">
                    <a href="#" class="transition-colors hover:text-primary">
                        Privacy Policy
                    </a>
                    <a href="#" class="transition-colors hover:text-primary">
                        Terms of Service
                    </a>
                </nav>
            </div>
        </footer>
    </div>
</template>

<style scoped></style>
