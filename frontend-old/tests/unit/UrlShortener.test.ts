import { mount } from '@vue/test-utils'
import { describe, expect, it, vi, beforeEach } from 'vitest'
import UrlShortener from '../../src/features/url-shortener/UrlShortener.vue'
import { createShortUrl } from '../../src/api/shortUrls'

vi.mock('../../src/api/shortUrls', () => ({
    createShortUrl: vi.fn(),
}))

describe('UrlShortener', () => {
    beforeEach(() => {
        vi.clearAllMocks()
    })

    it('shows an error and does not call the API for an invalid URL', async () => {
        const wrapper = mount(UrlShortener)

        const input = wrapper.find('input')
        await input.setValue('not-a-url')
        await input.trigger('keyup.enter')

        expect(wrapper.text()).toContain('Please enter a valid URL.')
        expect(createShortUrl).not.toHaveBeenCalled()
    })

    it('calls the API with the entered URL', async () => {
        vi.mocked(createShortUrl).mockResolvedValue({
            shortCode: 'abc123',
        })

        const wrapper = mount(UrlShortener)

        const input = wrapper.find('input')
        await input.setValue('https://example.com/test')
        await input.trigger('keyup.enter')

        expect(createShortUrl).toHaveBeenCalledWith(
            'https://example.com/test',
        )
    })

    it('sets the short code after a successful request', async () => {
        vi.mocked(createShortUrl).mockResolvedValue({
            shortCode: 'abc123',
        })

        const wrapper = mount(UrlShortener)

        const input = wrapper.find('input')
        await input.setValue('https://example.com')
        await input.trigger('keyup.enter')

        await vi.waitFor(() => {
            expect(wrapper.text()).toContain('abc123')
        })
    })

    it('sets an error when the API request fails', async () => {
        vi.mocked(createShortUrl).mockRejectedValue(
            new Error('API request failed'),
        )

        const wrapper = mount(UrlShortener)

        const input = wrapper.find('input')
        await input.setValue('https://example.com')
        await input.trigger('keyup.enter')

        await vi.waitFor(() => {
            expect(wrapper.text()).toContain(
                'Something went wrong. Please try again.',
            )
        })
    })

    it('resets the error and short code before a new request', async () => {
        vi.mocked(createShortUrl)
            .mockRejectedValueOnce(new Error('API request failed'))
            .mockResolvedValueOnce({
                shortCode: 'new123',
            })

        const wrapper = mount(UrlShortener)

        const input = wrapper.find('input')

        await input.setValue('https://example.com')
        await input.trigger('keyup.enter')

        await vi.waitFor(() => {
            expect(wrapper.text()).toContain(
                'Something went wrong. Please try again.',
            )
        })

        await input.setValue('https://example.org')
        await input.trigger('keyup.enter')

        await vi.waitFor(() => {
            expect(wrapper.text()).toContain('new123')
        })

        expect(wrapper.text()).not.toContain(
            'Something went wrong. Please try again.',
        )
    })
})