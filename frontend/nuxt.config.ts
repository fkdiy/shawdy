import tailwindcss from "@tailwindcss/vite";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: "2025-07-15",
  devtools: { enabled: true },
  css: ["~/assets/css/main.css"],

  runtimeConfig: {
    // Only accessible on the server-side
    apiBaseInternal: "http://frankenphp:8080/api",

    public: {
      // Also accessible on the client-side
      apiBase: "/api",
    },
  },

  vite: {
    server: {
      allowedHosts: ["node", "frankenphp"],
    },
    plugins: [tailwindcss()],
  },

  modules: ["@nuxt/eslint"],
});
