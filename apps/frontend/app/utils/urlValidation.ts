export function isValidUrl(urlInput: string): boolean {
  try {
    const parsedUrl = new URL(urlInput)

    return ['http:', 'https:'].includes(parsedUrl.protocol)
  } catch {
    return false
  }
}

export function isValidShawdyShortUrl(urlInput: string, hostname: string): boolean {
  try {
    const parsedUrl = new URL(urlInput)
    const pathSegments = parsedUrl.pathname.split('/').filter(Boolean)

    return ['http:', 'https:'].includes(parsedUrl.protocol)
      && parsedUrl.hostname === hostname
      && pathSegments.length === 1
      && /^[0-9a-zA-Z]+$/.test(pathSegments[0]!)
  } catch {
    return false
  }
}
