// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: [
    '@nuxt/eslint',
    '@nuxt/ui'
  ],

  devtools: {
    enabled: true
  },

  css: ['~/assets/css/main.css'],

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
  }
})
