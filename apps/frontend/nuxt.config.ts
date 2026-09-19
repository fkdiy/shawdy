// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: [
    '@nuxt/eslint',
    '@nuxt/content',
    '@nuxt/ui',
    '@nuxtjs/i18n',
    '@vueuse/nuxt',
    'motion-v/nuxt'
  ],

  devtools: {
    enabled: true
  },

  css: ['~/assets/css/main.css'],

  colorMode: {
    preference: 'dark',
    fallback: 'dark'
  },

  content: {
    experimental: {
      sqliteConnector: 'native'
    },
    renderer: {
      anchorLinks: false
    }
  },

  runtimeConfig: {
    apiBaseInternal: 'http://frankenphp:8080/api',

    public: {
      apiBase: '/api'
    }
  },

  compatibilityDate: '2026-06-30',

  vite: {
    server: {
      allowedHosts: ['node', 'frankenphp']
    }
  },

  eslint: {
    config: {
      stylistic: {
        commaDangle: 'never',
        braceStyle: '1tbs'
      }
    }
  },

  i18n: {
    locales: [
      { code: 'de', name: 'German', language: 'de-DE', dir: 'ltr', file: 'de.json' },
      { code: 'en', name: 'English', language: 'en-US', dir: 'ltr', file: 'en.json' }
    ],
    strategy: 'prefix_except_default',
    defaultLocale: 'de'
  },

  icon: {
    localApiEndpoint: '/app-assets/_nuxt_icon'
  }
})
