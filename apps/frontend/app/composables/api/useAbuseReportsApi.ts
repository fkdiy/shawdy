interface CreateAbuseReportRequest {
  shortUrl: string
  email: string
  message: string
  locale: 'de' | 'en'
}

interface CreateAbuseReportResponse {
  id: number
  shortCode: string
  email: string
  message: string
  locale: 'de' | 'en'
}

export function useAbuseReportsApi() {
  const { $api } = useNuxtApp()

  function createAbuseReport(
    data: CreateAbuseReportRequest
  ): Promise<CreateAbuseReportResponse> {
    return $api<CreateAbuseReportResponse>('/abuse_reports', {
      method: 'POST',
      body: data,
      headers: {
        'Content-Type': 'application/ld+json'
      }
    })
  }

  return {
    createAbuseReport
  }
}
