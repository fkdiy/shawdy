export async function usePageContent() {
  const route = useRoute()

  const asyncData = useAsyncData(
    `page:${route.path}`,
    () => queryCollection('content')
      .path(route.path)
      .first()
  )

  const page = asyncData.data

  const title = computed(() =>
    page.value?.seo?.title || page.value?.title
  )

  const description = computed(() =>
    page.value?.seo?.description || page.value?.description
  )

  useSeoMeta({
    title,
    ogTitle: title,
    description,
    ogDescription: description
  })

  // Erst NACH allen Nuxt-Composable-Aufrufen warten
  await asyncData

  if (!page.value) {
    throw createError({
      statusCode: 404,
      statusMessage: 'Page not found',
      fatal: true
    })
  }

  return {
    page
  }
}
