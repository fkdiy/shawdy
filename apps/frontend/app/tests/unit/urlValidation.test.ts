// @vitest-environment happy-dom

import { describe, expect, it } from 'vitest'
import { isValidUrl } from '../../utils/urlValidation'

describe('isValidUrl', () => {
  it('accepts HTTP URLs', () => {
    expect(isValidUrl('http://example.com')).toBe(true)
  })

  it('accepts URLs with query string', () => {
    expect(isValidUrl('http://example.com/script?query=true')).toBe(true)
  })

  it('accepts URLs with path', () => {
    expect(isValidUrl('http://example.com/has/path')).toBe(true)
  })

  it('accepts URLs with fragment', () => {
    expect(isValidUrl('http://example.com/url#fragment')).toBe(true)
  })

  it('accepts URLs with port', () => {
    expect(isValidUrl('http://example.com:8082')).toBe(true)
  })

  it('accepts HTTPS URLs', () => {
    expect(isValidUrl('https://example.com')).toBe(true)
  })

  it('rejects invalid URLs', () => {
    expect(isValidUrl('not-a-url')).toBe(false)
  })

  it('rejects relative URLs', () => {
    expect(isValidUrl('/relative/url.png')).toBe(false)
  })

  it('rejects non-HTTP protocols', () => {
    expect(isValidUrl('ftp://example.com')).toBe(false)
  })

  it('rejects JavaScript URLs', () => {
    expect(isValidUrl('javascript:alert("URL denied");')).toBe(false)
  })

  it('rejects data URLs', () => {
    expect(isValidUrl('data:text/plain;charset=utf-8,URL%20denied')).toBe(false)
  })

  it('rejects an empty string', () => {
    expect(isValidUrl('')).toBe(false)
  })
})
