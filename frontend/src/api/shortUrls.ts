import { apiClient } from './client'

interface CreateShortUrlResponse {
  shortCode: string
}

interface ShortUrlResponse {
  shortCode: string
  targetUrl: string
}

export async function createShortUrl(
  targetUrl: string,
): Promise<CreateShortUrlResponse> {
  const response = await apiClient.post<CreateShortUrlResponse>(
    '/short_urls',
    {
      targetUrl,
    },
  )

  return response.data
}

export async function getShortUrl(
  shortCode: string,
): Promise<ShortUrlResponse> {
  const response = await apiClient.get<ShortUrlResponse>(
    `/short_urls/${shortCode}`,
  )

  return response.data
}
