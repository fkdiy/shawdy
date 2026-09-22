interface CreateShortUrlResponse {
  shortCode: string
}

interface ShortUrlResponse {
  shortCode: string
  targetUrl: string
}

export function useShortUrlsApi() {
  const { $api } = useNuxtApp()

  function createShortUrl(targetUrl: string): Promise<CreateShortUrlResponse> {
    return $api<CreateShortUrlResponse>('/short_urls', {
      method: 'POST',
      body: {
        targetUrl
      },
      headers: {
        'Content-Type': 'application/ld+json'
      }
    })
  }

  function getShortUrl(shortCode: string): Promise<ShortUrlResponse> {
    return $api<ShortUrlResponse>(`/short_urls/${encodeURIComponent(shortCode)}`)
  }

  return {
    createShortUrl,
    getShortUrl
  }
}
