import { mockNuxtImport, mountSuspended } from "@nuxt/test-utils/runtime";

import { beforeEach, describe, expect, it, vi } from "vitest";

import UrlShortener from "~/components/url-shortener/UrlShortener.vue";

const { createShortUrlMock } = vi.hoisted(() => ({
  createShortUrlMock: vi.fn(),
}));

mockNuxtImport("useShortUrlsApi", () => {
  return () => ({
    createShortUrl: createShortUrlMock,
  });
});

describe("UrlShortener", () => {
  beforeEach(() => {
    createShortUrlMock.mockReset();
  });

  it("shows an error and does not call the API for an invalid URL", async () => {
    const wrapper = await mountSuspended(UrlShortener);
    const input = wrapper.find("input");

    await input.setValue("not-a-url");
    await input.trigger("keyup.enter");

    expect(wrapper.text()).toContain("Please enter a valid URL.");

    expect(createShortUrlMock).not.toHaveBeenCalled();
  });

  it("calls the API with the entered URL", async () => {
    createShortUrlMock.mockResolvedValue({
      shortCode: "abc123",
    });

    const wrapper = await mountSuspended(UrlShortener);
    const input = wrapper.find("input");

    await input.setValue("https://example.com/test");
    await input.trigger("keyup.enter");

    await vi.waitFor(() => {
      expect(createShortUrlMock).toHaveBeenCalledWith("https://example.com/test");
    });
  });

  it("sets the short code after a successful request", async () => {
    createShortUrlMock.mockResolvedValue({
      shortCode: "abc123",
    });

    const wrapper = await mountSuspended(UrlShortener);
    const input = wrapper.find("input");

    await input.setValue("https://example.com");
    await input.trigger("keyup.enter");

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain("abc123");
    });
  });

  it("sets an error when the API request fails", async () => {
    createShortUrlMock.mockRejectedValue(new Error("API request failed"));

    const wrapper = await mountSuspended(UrlShortener);
    const input = wrapper.find("input");

    await input.setValue("https://example.com");
    await input.trigger("keyup.enter");

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain("Something went wrong. Please try again.");
    });
  });

  it("resets the error and short code before a new request", async () => {
    createShortUrlMock
      .mockRejectedValueOnce(new Error("API request failed"))
      .mockResolvedValueOnce({
        shortCode: "new123",
      });

    const wrapper = await mountSuspended(UrlShortener);
    const input = wrapper.find("input");

    await input.setValue("https://example.com");
    await input.trigger("keyup.enter");

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain("Something went wrong. Please try again.");
    });

    await input.setValue("https://example.org");
    await input.trigger("keyup.enter");

    await vi.waitFor(() => {
      expect(wrapper.text()).toContain("new123");
    });

    expect(wrapper.text()).not.toContain("Something went wrong. Please try again.");
  });
});
