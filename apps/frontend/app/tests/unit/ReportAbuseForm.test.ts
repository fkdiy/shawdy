import { mockNuxtImport, mountSuspended } from '@nuxt/test-utils/runtime'
import { beforeEach, describe, expect, it, vi } from 'vitest'

import ReportAbuseForm from '~/components/ReportAbuseForm.vue'

const { createAbuseReportMock, toastMock } = vi.hoisted(() => ({
  createAbuseReportMock: vi.fn(),
  toastMock: vi.fn()
}))

vi.mock('~/composables/api/useAbuseReportsApi', () => ({
  useAbuseReportsApi: () => ({
    createAbuseReport: createAbuseReportMock
  })
}))

mockNuxtImport('usePageContent', () => {
  return async () => {
    const { ref } = await import('vue')

    return {
      page: ref({
        reportAbuseForm: {
          requiredFieldsInfo: 'Fields marked with * are required.',
          shortUrlLabel: 'Shawdy Short URL to report',
          shortUrlPlaceholder: 'Paste Shawdy Short-URL here ...',
          contactEmailLabel: 'Contact email address',
          contactEmailPlaceholder: 'Enter contact email address here ...',
          descriptionLabel: 'Description of the incident',
          descriptionPlaceholder: 'Enter optional additional info here ...',
          submitLabel: 'Send report'
        }
      })
    }
  }
})

mockNuxtImport('useI18n', () => {
  return () => ({
    locale: ref('de'),
    t: (key: string) => key
  })
})

mockNuxtImport('useToast', () => {
  return () => ({
    add: toastMock
  })
})

describe('ReportAbuseForm', () => {
  beforeEach(() => {
    createAbuseReportMock.mockReset()
    toastMock.mockReset()
  })

  async function mountReportAbuseForm() {
    const wrapper = await mountSuspended(ReportAbuseForm)

    return {
      wrapper,
      shortUrl: wrapper.get<HTMLInputElement>('#shortUrl'),
      email: wrapper.get<HTMLInputElement>('#email'),
      message: wrapper.get<HTMLTextAreaElement>('#message'),
      form: wrapper.get('form')
    }
  }

  it('shows an error and does not call the API for an invalid short URL', async () => {
    const { wrapper, shortUrl, email, form } = await mountReportAbuseForm()

    await shortUrl.setValue('not-a-url')
    await email.setValue('mail@test.com')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(wrapper.find('[data-slot="error"]').exists()).toBe(true)
    })

    expect(createAbuseReportMock).not.toHaveBeenCalled()
  })

  it('shows an error and does not call the API for an invalid email address', async () => {
    const { wrapper, shortUrl, email, form } = await mountReportAbuseForm()

    await shortUrl.setValue('http://localhost/abc123')
    await email.setValue('not-an-email')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(wrapper.find('[data-slot="error"]').exists()).toBe(true)
    })

    expect(createAbuseReportMock).not.toHaveBeenCalled()
  })

  it('calls the API with a valid form', async () => {
    createAbuseReportMock.mockResolvedValue(undefined)

    const {
      shortUrl,
      email,
      message,
      form
    } = await mountReportAbuseForm()

    await shortUrl.setValue('http://localhost/abc123')
    await email.setValue('mail@test.com')
    await message.setValue('This URL contains abusive content.')

    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(createAbuseReportMock).toHaveBeenCalledWith({
        shortUrl: 'http://localhost/abc123',
        email: 'mail@test.com',
        message: 'This URL contains abusive content.',
        locale: 'de'
      })
    })
  })

  it('shows the toast after a successful request', async () => {
    createAbuseReportMock.mockResolvedValue(undefined)

    const {
      shortUrl,
      email,
      message,
      form
    } = await mountReportAbuseForm()

    await shortUrl.setValue('http://localhost/abc123')
    await email.setValue('mail@test.com')
    await message.setValue('This URL contains abusive content.')

    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(createAbuseReportMock).toHaveBeenCalledWith({
        shortUrl: 'http://localhost/abc123',
        email: 'mail@test.com',
        message: 'This URL contains abusive content.',
        locale: 'de'
      })
    })

    await vi.waitFor(() => {
      expect(toastMock).toHaveBeenCalledWith(
        expect.objectContaining({
          title: 'common.thankYou',
          description: 'common.messageSubmitted'
        })
      )
    })
  })

  it('clears the form after a successful request', async () => {
    createAbuseReportMock.mockResolvedValue(undefined)

    const {
      shortUrl,
      email,
      message,
      form
    } = await mountReportAbuseForm()

    await shortUrl.setValue('http://localhost/abc123')
    await email.setValue('mail@test.com')
    await message.setValue('This URL contains abusive content.')

    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(createAbuseReportMock).toHaveBeenCalled()
    })

    expect(shortUrl.element.value).toBe('')
    expect(email.element.value).toBe('')
    expect(message.element.value).toBe('')
  })

  it('shows an error when the API request fails', async () => {
    createAbuseReportMock.mockRejectedValue(
      new Error('API request failed')
    )

    const {
      wrapper,
      shortUrl,
      email,
      message,
      form
    } = await mountReportAbuseForm()

    await shortUrl.setValue('http://localhost/abc123')
    await email.setValue('mail@test.com')
    await message.setValue('This URL contains abusive content.')

    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(wrapper.find('[data-slot="error"]').exists()).toBe(true)
    })
  })
})
