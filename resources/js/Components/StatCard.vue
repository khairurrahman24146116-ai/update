<template>
  <div class="flex flex-col gap-2.5 border border-outline-variant rounded-xl bg-surface-card shadow-sm p-5">
    <div class="flex items-start justify-between gap-2">
      <span
        v-if="icon"
        class="material-symbols-outlined !w-6 !h-6 text-on-surface-variant"
      >{{ icon }}</span>
      <span
        class="inline-flex items-center justify-end font-code-md text-code-md uppercase tracking-wider"
        :class="toneClasses"
      >{{ label }}</span>
    </div>
    <div class="font-headline-lg text-headline-lg font-semibold tracking-tight text-on-surface leading-none">
      <template v-if="hasValue">{{ value }}<span v-if="suffix" class="ml-1 font-body-md text-body-md font-medium text-on-surface-variant">{{ suffix }}</span></template>
      <span v-else class="text-on-surface-variant">—</span>
    </div>
    <p v-if="caption" class="font-body-sm text-body-sm text-on-surface-variant">{{ caption }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], default: null },
  suffix: { type: String, default: '' },
  caption: { type: String, default: '' },
  icon: { type: String, default: '' },
  tone: { type: String, default: 'primary' },
})

const hasValue = computed(() => props.value !== null && props.value !== undefined && props.value !== '')

const tones = {
  primary: 'text-primary',
  secondary: 'text-secondary',
  tertiary: 'text-tertiary',
  neutral: 'text-ink-muted',
}

const toneClasses = computed(() => tones[props.tone] || tones.primary)
</script>