<template>
  <div class="flex flex-col gap-1 md:flex-row md:items-end md:justify-between md:gap-4">
    <div class="flex flex-col gap-1">
      <span v-if="eyebrow" class="font-code-md text-code-md uppercase tracking-wider text-primary">{{ eyebrow }}</span>
      <h1 class="font-headline-lg text-headline-lg font-semibold tracking-tight text-on-surface">{{ title }}</h1>
      <p v-if="subtitle" class="font-body-md text-body-md text-on-surface-variant">{{ subtitle }}</p>
    </div>
    <div class="flex items-center gap-2">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
  eyebrow: { type: String, default: '' },
})
</script>