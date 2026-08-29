import { test, expect } from '@playwright/test'

test('shortens a URL', async ({ page }) => {
    await page.goto('/')

    await page
        .getByPlaceholder('Paste your long URL here...')
        .fill('https://example.com')

    await page
        .getByRole('button', { name: 'Shorten' })
        .click()

    await expect(page.getByText('Your short URL:')).toBeVisible()
})