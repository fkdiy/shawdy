export function isValidUrl(urlInput: string): boolean {
  try {
    const parsedUrl = new URL(urlInput);

    return ["http:", "https:"].includes(parsedUrl.protocol);
  } catch {
    return false;
  }
}
