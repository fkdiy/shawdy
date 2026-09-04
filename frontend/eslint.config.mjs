// @ts-check
import withNuxt from "./.nuxt/eslint.config.mjs";
import oxlint from "eslint-plugin-oxlint";

export default withNuxt({
  rules: {
    "vue/html-self-closing": [
      "warn",
      {
        html: {
          void: "any",
          normal: "always",
          component: "always",
        },
        svg: "always",
        math: "always",
      },
    ],
  },
})
  // Disable overlapping rules so ESLint only evaluates what Oxlint can't
  .append(oxlint.configs["flat/recommended"]);
