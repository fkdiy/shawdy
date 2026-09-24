export function isRateLimitError(error: unknown): boolean {
  return (
    typeof error === 'object'
    && error !== null
    && 'statusCode' in error
    && error.statusCode === 429
  )
}
