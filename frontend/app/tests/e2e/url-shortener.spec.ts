import { expect, test } from "@playwright/test";

test("creates and displays a short URL", async ({ page }) => {
  await page.goto("/");

  await page.waitForFunction(() => {
    const nuxtRoot = document.querySelector("#__nuxt");

    return Boolean(nuxtRoot && "__vue_app__" in nuxtRoot);
  });

  await page.locator("input").fill("https://example.com/test");

  const apiResponsePromise = page.waitForResponse((response) => {
    return response.request().method() === "POST" && response.url().includes("/api/short_urls");
  });

  await page.getByRole("button", { name: "Shorten" }).click();

  const apiResponse = await apiResponsePromise;

  expect(apiResponse.status()).toBe(201);

  await expect(
    page.getByText("Your short URL:", {
      exact: true,
    }),
  ).toBeVisible();

  const resultLink = page.getByTestId("short-url-result");

  await expect(resultLink).toBeVisible();

  await expect(resultLink).toHaveAttribute("href", /\/[a-zA-Z0-9]+$/);
});
