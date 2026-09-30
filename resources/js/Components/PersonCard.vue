<template>
  <div
    :class="layout === 'featured'
      ? 'bg-surface-container-low p-space-md flex flex-col sm:flex-row gap-space-md items-start shadow-sm'
      : 'bg-surface-container-low p-space-md flex flex-col justify-between shadow-sm'"
  >
    <div
      class="overflow-hidden bg-surface-container shadow-sm shrink-0"
      :class="layout === 'featured' ? 'w-32 sm:w-44 aspect-[4/5]' : 'w-full aspect-[4/5] mb-space-sm'"
    >
      <img
        class="w-full h-full object-cover"
        :src="figure.photo"
        :alt="altText"
        :style="{ objectPosition: figure.objectPosition || '50% 15%' }"
      />
    </div>
    <div :class="layout === 'featured' ? 'flex-1' : 'flex flex-col'">
      <h3 class="font-title-md text-title-md font-bold text-on-surface">{{ figure.name }}</h3>
      <span v-if="figure.role" class="font-label-sm text-label-sm font-semibold mt-0.5 block" :class="figure.roleColor || 'text-primary'">{{ figure.role }}</span>
      <p v-if="figure.bio" class="font-body-sm text-body-sm text-on-surface-variant mt-2 leading-relaxed">{{ figure.bio }}</p>
      <ul v-if="figure.riwayat && figure.riwayat.length" class="mt-3 space-y-1.5 font-body-sm text-body-sm">
        <li v-for="r in figure.riwayat" :key="r.deg" class="flex items-start gap-2">
          <span class="material-symbols-outlined text-[16px] mt-0.5 text-primary">school</span>
          <span><span class="font-semibold text-on-surface">{{ r.deg }}:</span> {{ r.inst }}</span>
        </li>
      </ul>
    </div>
    <div v-if="figure.subject && layout === 'grid'" class="mt-space-md pt-space-xs font-code-md text-code-md text-outline">Pengampu: {{ figure.subject }}</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  figure: { type: Object, required: true },
  layout: { type: String, default: 'grid', validator: (l) => ['featured', 'grid'].includes(l) },
})

const altText = computed(() => props.figure.alt || props.figure.name)
</script>