import eslint from '@eslint/js'
import vue from 'eslint-plugin-vue'
import tseslint from 'typescript-eslint'
import globals from 'globals'
import { defineConfig } from 'eslint/config'

export default defineConfig([
    // 1. Core recommended shareable configs
    eslint.configs.recommended,
    ...tseslint.configs.recommended,
    ...vue.configs['flat/recommended'],

    // 2. Vue file specific language options
    {
        files: ['**/*.vue'],
        languageOptions: {
            globals: globals.browser,
            parserOptions: {
                parser: tseslint.parser,
            },
        },
    },

    // 3. Custom project rules override
    {
        rules: {
            '@typescript-eslint/no-unused-vars': [
                'error',
                {
                    varsIgnorePattern: '^ALLOWED_',
                },
            ],
            'vue/html-indent': 'off',
            'vue/max-attributes-per-line': 'off',
            'vue/multiline-html-element-content-newline': 'off',
            'vue/attributes-order': 'off',
            'vue/html-self-closing': 'off',
            'vue/singleline-html-element-content-newline': 'off',
        },
    },
])