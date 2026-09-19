import type { Collections } from '@nuxt/content'

export async function usePageContent() {
  const route = useRoute()
  const routeBaseName = useRouteBaseName()
  const { locale } = useI18n()

  const contentPath = computed(() => {
    const name = routeBaseName(route)

    if (typeof name !== 'string') {
      return '/'
    }

    return name === 'index'
      ? '/'
      : `/${name}`
  })

  const asyncData = useAsyncData(
    computed(() => `page:${locale.value}:${contentPath.value}`),
    async () => {
      const collection
        = `content_${locale.value}` as keyof Collections

      return await queryCollection(collection)
        .path(contentPath.value)
        .first()
    }
  )

  const pageData = asyncData.data

  const title = computed(() =>
    pageData.value?.seo?.title || pageData.value?.title
  )

  const description = computed(() =>
    pageData.value?.seo?.description || pageData.value?.description
  )

  useSeoMeta({
    title,
    ogTitle: title,
    description,
    ogDescription: description
  })

  await asyncData

  const page = computed(() => {
    if (!pageData.value) {
      console.error('[Shawdy content]', {
        route: route.path,
        routeName: route.name,
        baseName: routeBaseName(route),
        locale: locale.value,
        collection: `content_${locale.value}`,
        contentPath: contentPath.value,
        status: asyncData.status.value,
        data: pageData.value,
        error: asyncData.error.value
      })

      throw createError({
        statusCode: 404,
        statusMessage: 'Page not found',
        fatal: true
      })
    }

    return pageData.value
  })

  return {
    page
  }
}
