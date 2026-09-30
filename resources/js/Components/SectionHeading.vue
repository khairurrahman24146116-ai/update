<template>
  <div class="flex flex-col gap-2.5" :class="align === 'center' ? 'items-center text-center mx-auto' : 'items-start'">
    <span
      class="inline-flex items-center gap-1.5 font-code-md text-code-md uppercase tracking-wider text-primary"
      :class="align === 'center' ? '' : 'border-l-4 border-primary pl-2'"
    >
      {{ eyebrow }}
    </span>
    <h2 class="font-headline-md text-headline-md md:font-headline-lg md:text-headline-lg font-semibold tracking-tight text-on-surface max-w-3xl">
      <slot name="title">{{ title }}</slot>
    </h2>
    <p v-if="description || $slots.description" class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
      <slot name="description">{{ description }}</slot>
    </p>
  </div>
</template>

<script setup>
defineProps({
  eyebrow: { type: String, default: '' },
  title: { type: String, default: '' },
  description: { type: String, default: '' },
  align: { type: String, default: 'left' },
})
</script>