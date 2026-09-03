<script setup lang="ts">
import AppCard from '../../components/AppCard.vue'
import AppInputText from '../../components/AppInputText.vue'
import AppButton from '../../components/AppButton.vue'

import { ref } from 'vue'
import { createShortUrl } from '../../api/shortUrls'
import { isValidUrl } from './urlValidation'

const targetUrl = ref('')
const shortCode = ref('')
const isLoading = ref(false)
const error = ref('')

async function shortenUrl() {
  const url = targetUrl.value.trim()

  if (!isValidUrl(url)) {
    error.value = 'Please enter a valid URL.'
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
  <AppCard>
    <div class="mb-6 flex items-center gap-3">
      <img src="/icon-link.svg" alt="" class="h-6" />

      <h2 class="text-xl font-semibold leading-none text-text-primary">Shorten your link</h2>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-end w-full gap-4">
      <AppInputText
        v-model="targetUrl"
        type="text"
        id="targetUrl"
        placeholder="Paste your long URL here..."
        :error="error"
        @keyup.enter="shortenUrl"
      />

      <AppButton variant="primary" @click="shortenUrl" :loading="isLoading"> Shorten </AppButton>
    </div>
  </AppCard>

  <AppCard v-if="shortCode && !error" variant="contrast">
    <p class="text-m text-text-primary mb-4">Your short URL:</p>
    <a
      :href="`http://localhost:8080/${shortCode}`"
      target="_blank"
      class="text-primary text-xl font-semibold hover:text-text"
    >
      http://localhost:8080/{{ shortCode }}
    </a>
  </AppCard>
</template>
