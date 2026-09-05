export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig();

  const forwardedHeaders = import.meta.server
    ? useRequestHeaders(["cookie", "authorization", "accept-language"])
    : undefined;

  const api = $fetch.create({
    baseURL: import.meta.server ? config.apiBaseInternal : config.public.apiBase,

    headers: forwardedHeaders,

    onRequest({ options }) {
      const headers = new Headers(options.headers);

      if (!headers.has("Accept")) {
        headers.set("Accept", "application/ld+json");
      }

      options.headers = headers;
    },
  });

  return {
    provide: {
      api,
    },
  };
});
