<script setup lang="ts">
import { isValidUrl } from "~/utils/urlValidation";

const targetUrl = ref("");
const shortCode = ref("");
const isLoading = ref(false);
const error = ref("");

const { createShortUrl } = useShortUrlsApi();
const requestUrl = useRequestURL();

const shortUrl = computed(() => {
  if (!shortCode.value) {
    return "";
  }

  return new URL(`/${encodeURIComponent(shortCode.value)}`, requestUrl.origin).toString();
});

async function shortenUrl() {
  const url = targetUrl.value.trim();

  if (!isValidUrl(url)) {
    error.value = "Please enter a valid URL.";
    return;
  }

  isLoading.value = true;
  error.value = "";
  shortCode.value = "";

  try {
    const response = await createShortUrl(targetUrl.value.trim());
    shortCode.value = response.shortCode;
  } catch {
    error.value = "Something went wrong. Please try again.";
  } finally {
    isLoading.value = false;
  }
}
</script>

<template>
  <AppCard>
    <div class="mb-6 flex items-center gap-3">
      <img src="/app-assets/icon-link.svg" alt="" class="h-6" />

      <h2 class="text-xl font-semibold leading-none text-text-primary">Shorten your link</h2>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-end w-full gap-4">
      <AppInputText
        id="targetUrl"
        v-model="targetUrl"
        type="text"
        placeholder="Paste your long URL here..."
        :error="error"
        @keyup.enter="shortenUrl"
      />

      <AppButton variant="primary" :loading="isLoading" @click="shortenUrl"> Shorten </AppButton>
    </div>
  </AppCard>

  <AppCard v-if="shortCode && !error" variant="contrast">
    <p class="text-m text-text-primary mb-4">Your short URL:</p>
    <a
      :href="shortUrl"
      target="_blank"
      rel="noopener noreferrer"
      class="text-primary text-xl font-semibold hover:text-text"
      data-testid="short-url-result"
    >
      {{ shortUrl }}
    </a>
  </AppCard>
</template>
