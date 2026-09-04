import { expect, test } from "@playwright/test";

test("shortens a URL", async ({ page }) => {
  test.setTimeout(90_000);

  page.on("console", (message) => {
    console.log(`[browser:${message.type()}] ${message.text()}`);
  });

  page.on("pageerror", (error) => {
    console.error(`[browser:pageerror] ${error.message}`);
  });

  page.on("requestfailed", (request) => {
    console.error(
      `[browser:requestfailed] ${request.method()} ${request.url()} – ${
        request.failure()?.errorText ?? "unknown error"
      }`,
    );
  });

  page.on("request", (request) => {
    if (request.url().includes("/api/")) {
      console.log(`[browser:request] ${request.method()} ${request.url()}`);
    }
  });

  page.on("response", (response) => {
    if (response.url().includes("/api/")) {
      console.log(
        `[browser:response] ${response.status()} ${response.request().method()} ${response.url()}`,
      );
    }
  });

  await page.goto("/", {
    waitUntil: "domcontentloaded",
    timeout: 60_000,
  });

  await page.waitForFunction(
    () => {
      const nuxtRoot = document.querySelector("#__nuxt") as
        | (HTMLElement & { __vue_app__?: unknown })
        | null;

      return Boolean(nuxtRoot?.__vue_app__);
    },
    undefined,
    { timeout: 60_000 },
  );

  const input = page.getByPlaceholder("Paste your long URL here...");
  const button = page.getByRole("button", { name: "Shorten" });

  await expect(input).toBeVisible();
  await expect(button).toBeVisible();
  await expect(button).toBeEnabled();

  await input.fill("https://example.com/test");

  const apiResponsePromise = page.waitForResponse(
    (response) =>
      response.request().method() === "POST" && response.url().includes("/api/short_urls"),
    { timeout: 60_000 },
  );

  await button.click();

  const apiResponse = await apiResponsePromise;

  console.log(`[test] API response: ${apiResponse.status()} ${apiResponse.url()}`);

  expect(apiResponse.ok()).toBe(true);

  await expect(page.getByTestId("short-url-result")).toBeVisible({
    timeout: 30_000,
  });

  await expect(page.getByText("Your short URL:")).toBeVisible();
});
