import { defineConfig } from '@playwright/test'

export default defineConfig({
    testDir: './tests/e2e',
    use: {
        baseURL: 'http://node:5173',
    },

    outputDir: './tests/e2e/test-results',
    preserveOutput: 'failures-only',
})