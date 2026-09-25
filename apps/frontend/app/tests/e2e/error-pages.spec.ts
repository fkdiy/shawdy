import { expect, test } from '@playwright/test'

test('shows the Caddy error page for an unknown route', async ({ page }) => {
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

  const response = await page.goto('/does/not/exist', {
    waitUntil: 'domcontentloaded',
    timeout: 60_000
  })

  expect(response?.status()).toBe(404)

  await expect(
    page.getByText('Error 404')
  ).toBeVisible()

  await expect(
    page.getByText(
      'The page you tried to open isn\'t available or doesn\'t exist.'
    )
  ).toBeVisible()
})

test('shows the shared error page for an unknown short code', async ({ page }) => {
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

  const response = await page.goto('/definitelynotavalidshortcode')

  expect(response?.status()).toBe(404)

  await expect(
    page.getByText('Error 404')
  ).toBeVisible()
})
