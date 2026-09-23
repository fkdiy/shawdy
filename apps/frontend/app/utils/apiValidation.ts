import type { FormError } from '@nuxt/ui'
import type { FetchError } from 'ofetch'

type ApiViolation = {
  propertyPath: string
  message: string
}

type ApiValidationErrorResponse = {
  violations?: ApiViolation[]
}

export function getApiValidationErrors(
  error: unknown,
  translate: (key: string) => string
): FormError[] {
  const fetchError = error as FetchError<ApiValidationErrorResponse>

  if (
    fetchError.statusCode !== 422
    || !fetchError.data?.violations
  ) {
    return []
  }

  return fetchError.data.violations.map(violation => ({
    name: violation.propertyPath,
    message: translate(violation.message)
  }))
}
