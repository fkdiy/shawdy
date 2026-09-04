import process from "node:process";
import { defineConfig } from "@playwright/test";

export default defineConfig({
  testDir: "./app/tests/e2e",
  use: {
    baseURL: process.env.PLAYWRIGHT_BASE_URL || "http://localhost",
  },

  outputDir: "./app/tests/e2e/test-results",
  preserveOutput: "failures-only",
  reporter: [["html", { outputFolder: "./app/tests/e2e/playwright-report" }], ["github"]],
});
