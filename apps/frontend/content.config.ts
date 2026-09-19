import { defineCollection, defineContentConfig, z } from '@nuxt/content'

const createEnum = (options: [string, ...string[]]) => z.enum(options)

const createSeoSchema = () => z.object({
  title: z.string().nonempty(),
  description: z.string().nonempty()
})

const createLinkSchema = () => z.object({
  label: z.string().nonempty(),
  to: z.string().nonempty(),
  icon: z.string().optional().editor({ input: 'icon' }),
  trailingIcon: z.string().optional().editor({ input: 'icon' }),
  size: createEnum(['xs', 'sm', 'md', 'lg', 'xl']).optional(),
  trailing: z.boolean().optional(),
  target: createEnum(['_blank', '_self']).optional(),
  color: createEnum(['primary', 'secondary', 'neutral', 'error', 'warning', 'success', 'info']).optional(),
  variant: createEnum(['solid', 'outline', 'subtle', 'soft', 'ghost', 'link']).optional()
})

const createUrlShortenerSchema = () => z.object({
  icon: z.string(),
  cta: z.string(),
  placeholder: z.string(),
  buttonLabel: z.string(),
  resultTitle: z.string(),
  validationError: z.string(),
  generalError: z.string(),
  copyToClipboardAria: z.string()
})

const createHeroSchema = () => z.object({
  headline: z.string().nonempty(),
  title: z.string().nonempty(),
  description: z.string().nonempty(),
  links: z.array(createLinkSchema()),
  urlShortener: createUrlShortenerSchema()
})

const createPageHeaderSchema = () => z.object({
  headline: z.string().optional(),
  title: z.string().nonempty(),
  description: z.string().nonempty()
})

const createPageSectionSchema = () => z.object({
  headline: z.string().optional(),
  title: z.string().nonempty(),
  description: z.string().nonempty(),
  items: z.array(z.object({
    icon: z.string(),
    title: z.string().nonempty(),
    description: z.string().nonempty()
  })).optional()
})

const commonSchema = z.object({
  seo: createSeoSchema(),
  hero: createHeroSchema().optional(),
  pageHeader: createPageHeaderSchema(),
  pageSection: createPageSectionSchema().optional()
})

export default defineContentConfig({
  collections: {
    content_de: defineCollection({
      type: 'page',
      source: {
        include: 'de/**',
        prefix: '/'
      },
      schema: commonSchema
    }),

    content_en: defineCollection({
      type: 'page',
      source: {
        include: 'en/**',
        prefix: '/'
      },
      schema: commonSchema
    })
  }
})
