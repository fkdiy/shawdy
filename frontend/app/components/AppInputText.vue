<script setup lang="ts">
const _ALLOWED_TYPES = ["text", "url", "password", "email", "search", "tel"] as const;

export type InputType = (typeof _ALLOWED_TYPES)[number];

defineOptions({
  inheritAttrs: false,
});

const model = defineModel<string>({ default: "" });

const {
  label = "",
  error = "",
  type = "text",
} = defineProps<{
  id: string;
  label?: string;
  error?: string;
  type?: InputType;
}>();
</script>

<template>
  <div class="flex-col flex-1 grid gap-2">
    <label v-if="label" :for="id" class="block text-m text-text-muted">
      {{ label }}
    </label>

    <div class="relative">
      <input
        :id="id"
        v-model="model"
        :type="type"
        v-bind="$attrs"
        class="w-full min-w-0 flex flex-col rounded-md border border-border bg-input px-4 py-3 text-text-primary placeholder:text-input-placeholder outline-none drop-shadow-glow/0 transition-all focus:drop-shadow-glow/50 focus:border-brand"
        :class="{
          'border-error': error,
        }"
      />

      <span
        v-if="error"
        class="absolute text-xs text-badge-error rounded-sm border border-badge-error bg-badge-error-muted px-4 py-2 mt-2"
      >
        {{ error }}
      </span>
    </div>
  </div>
</template>
