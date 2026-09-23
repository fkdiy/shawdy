import { defineConfig } from 'vitest/config'
import { defineVitestProject } from '@nuxt/test-utils/config'

export default defineConfig({
  test: {
    projects: [
      {
        test: {
          name: 'unit',
          environment: 'node',

          include: [
            'app/tests/unit/urlValidation.test.ts'
          ],

          exclude: [
            '**/node_modules/**',
            '**/dist/**',
            '**/tests/e2e/**'
          ]
        }
      },

      await defineVitestProject({
        test: {
          name: 'nuxt',
          environment: 'nuxt',

          include: [
            'app/tests/unit/HeroUrlShortener.test.ts'
          ],

          exclude: [
            '**/node_modules/**',
            '**/dist/**',
            '**/tests/e2e/**'
          ],

          environmentOptions: {
            nuxt: {
              overrides: {
                content: {
                  _localDatabase: {
                    type: 'sqlite',
                    filename: '.data/content/test.sqlite'
                  },

                  experimental: {
                    sqliteConnector: 'native'
                  }
                }
              }
            }
          }
        }
      })
    ]
  }
})
