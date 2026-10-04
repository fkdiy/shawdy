import { expect, test } from '@playwright/test'

test('submits an abuse report', async ({ page, request }) => {
  test.setTimeout(90_000)

  page.on('console', (message) => {
    console.log(`[browser:${message.type()}] ${message.text()}`)
  })

  page.on('pageerror', (error) => {
    console.error(`[browser:pageerror] ${error.message}`)
  })

  page.on('requestfailed', (request) => {
    console.error(
      `[browser:requestfailed] ${request.method()} ${request.url()} – ${
        request.failure()?.errorText ?? 'unknown error'
      }`
    )
  })

  page.on('request', (request) => {
    if (request.url().includes('/api/')) {
      console.log(`[browser:request] ${request.method()} ${request.url()}`)
    }
  })

  page.on('response', (response) => {
    if (response.url().includes('/api/')) {
      console.log(
        `[browser:response] ${response.status()} ${response.request().method()} ${response.url()}`
      )
    }
  })

  await page.goto('/report-abuse', {
    waitUntil: 'domcontentloaded',
    timeout: 60_000
  })

  await page.waitForFunction(
    () => {
      const nuxtRoot = document.querySelector('#__nuxt') as
        | (HTMLElement & { __vue_app__?: unknown })
        | null

      return Boolean(nuxtRoot?.__vue_app__)
    },
    undefined,
    { timeout: 60_000 }
  )

  // Create valid short code
  const shortUrlResponse = await request.post(
    'http://frankenphp:8082/api/short_urls',
    {
      headers: {
        'Content-Type': 'application/ld+json'
      },
      data: {
        targetUrl: 'https://example.com'
      }
    }
  )

  expect(shortUrlResponse.ok()).toBeTruthy()

  const shortUrlData = await shortUrlResponse.json()
  const shortCode = shortUrlData.shortCode

  const currentUrl = new URL(page.url())

  const inputShortUrl = page.getByTestId('short-url-input')
  const inputEmail = page.getByTestId('email-input')
  const inputMessage = page.getByTestId('message-textarea')
  const button = page.getByTestId('abuse-report-submit')

  await expect(inputShortUrl).toBeVisible()
  await expect(inputEmail).toBeVisible()
  await expect(inputMessage).toBeVisible()
  await expect(button).toBeVisible()
  await expect(button).toBeEnabled()

  await inputShortUrl.fill(
    `${currentUrl.origin}/${shortCode}`
  )
  await inputEmail.fill('test@email.com')

  const apiResponsePromise = page.waitForResponse(
    response =>
      response.request().method() === 'POST'
      && response.url().includes('/api/abuse_reports'),
    { timeout: 60_000 }
  )

  await button.click()

  const apiResponse = await apiResponsePromise

  expect(apiResponse.ok()).toBe(true)
})
