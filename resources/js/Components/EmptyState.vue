<template>
  <div class="flex flex-col items-center justify-center gap-3 border border-dashed border-outline-variant rounded bg-surface-container-low px-6 py-10 text-center">
    <span class="flex h-14 w-14 items-center justify-center rounded-full border border-outline-variant bg-surface-container-high shadow-sm">
      <span class="material-symbols-outlined !w-7 !h-7 text-primary">{{ icon }}</span>
    </span>
    <div class="flex flex-col items-center gap-1">
      <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">{{ title }}</h3>
      <p v-if="description" class="max-w-md font-body-md text-body-md text-on-surface-variant">{{ description }}</p>
    </div>
    <span v-if="showGap" class="inline-flex items-center gap-1 font-code-md text-code-md uppercase tracking-wider rounded-full border border-tertiary-container bg-tertiary-fixed text-on-tertiary-container px-2 py-0.5">
      <span class="material-symbols-outlined !w-4 !h-4">engineering</span>
      BACKEND GAP
    </span>
    <router-link
      v-if="to"
      :to="to"
      class="btn btn--primary"
    >{{ actionLabel }}</router-link>
    <slot name="actions" />
  </div>
</template>

<script setup>
defineProps({
  icon: { type: String, default: 'construction' },
  title: { type: String, default: 'Modul belum tersedia' },
  description: { type: String, default: '' },
  actionLabel: { type: String, default: 'Kembali' },
  to: { type: [String, Object], default: '' },
  showGap: { type: Boolean, default: false },
})
</script>