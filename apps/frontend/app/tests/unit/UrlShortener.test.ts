import { mockNuxtImport, mountSuspended } from '@nuxt/test-utils/runtime'
import { beforeEach, describe, expect, it, vi } from 'vitest'

import UrlShortener from '~/components/UrlShortener.vue'

const { createShortUrlMock } = vi.hoisted(() => ({
  createShortUrlMock: vi.fn()
}))

mockNuxtImport('useShortUrlsApi', () => {
  return () => ({
    createShortUrl: createShortUrlMock
  })
})

describe('UrlShortener', () => {
  beforeEach(() => {
    createShortUrlMock.mockReset()
  })

  async function mountShortener() {
    const wrapper = await mountSuspended(UrlShortener)

    return {
      wrapper,
      input: wrapper.find('#targetUrl'),
      form: wrapper.find('form')
    }
  }

  it('shows an error and does not call the API for an invalid URL', async () => {
    const { wrapper, input, form } = await mountShortener()

    await input.setValue('not-a-url')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain('Please enter a valid URL.')
    })

    expect(createShortUrlMock).not.toHaveBeenCalled()
  })

  it('calls the API with the entered URL', async () => {
    createShortUrlMock.mockResolvedValue({
      shortCode: 'abc123'
    })

    const { input, form } = await mountShortener()

    await input.setValue('https://example.com/test')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(createShortUrlMock).toHaveBeenCalledWith(
        'https://example.com/test'
      )
    })
  })

  it('trims the URL before sending it to the API', async () => {
    createShortUrlMock.mockResolvedValue({
      shortCode: 'abc123'
    })

    const { input, form } = await mountShortener()

    await input.setValue('  https://example.com/test  ')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(createShortUrlMock).toHaveBeenCalledWith(
        'https://example.com/test'
      )
    })
  })

  it('shows the short URL after a successful request', async () => {
    createShortUrlMock.mockResolvedValue({
      shortCode: 'abc123'
    })

    const { wrapper, input, form } = await mountShortener()

    await input.setValue('https://example.com')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain('abc123')
    })
  })

  it('shows an error when the API request fails', async () => {
    createShortUrlMock.mockRejectedValue(
      new Error('API request failed')
    )

    const { wrapper, input, form } = await mountShortener()

    await input.setValue('https://example.com')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain(
        'Something went wrong. Please try again.'
      )
    })
  })

  it('clears the previous error before a new request', async () => {
    createShortUrlMock
      .mockRejectedValueOnce(new Error('API request failed'))
      .mockResolvedValueOnce({
        shortCode: 'new123'
      })

    const { wrapper, input, form } = await mountShortener()

    await input.setValue('https://example.com')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain(
        'Something went wrong. Please try again.'
      )
    })

    await input.setValue('https://example.org')
    await form.trigger('submit')

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain('new123')
    })

    expect(wrapper.text()).not.toContain(
      'Something went wrong. Please try again.'
    )
  })
})
