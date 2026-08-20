import { defineConfig } from "cypress";

export default defineConfig({
  allowCypressEnv: false,
  e2e: {
    baseUrl: "http://127.0.0.1:8000",
    supportFile: "cypress/support/e2e.js",
    viewportWidth: 1440,
    viewportHeight: 900,
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});