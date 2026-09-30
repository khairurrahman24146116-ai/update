<template>
  <span
    class="inline-flex shrink-0 items-center justify-center overflow-hidden border border-outline-variant bg-surface-container-lowest rounded-full shadow-sm"
    :class="[`rounded-${shape === 'pill' ? 'full' : shape}`]"
    :style="dims"
    aria-hidden="true"
  >
    <img
      :src="currentSrc"
      :alt="alt"
      class="h-full w-full object-contain"
      loading="lazy"
      @error="currentSrc = fallback"
    />
  </span>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  src: { type: String, default: '' },
  alt: { type: String, default: 'Logo SMA Madani Al Aziziyah' },
  size: { type: [Number, String], default: 40 },
  shape: { type: String, default: 'lg' },
})

const fallback = '/images/emblem.svg'
const currentSrc = ref(props.src || fallback)

watch(
  () => props.src,
  (val) => {
    currentSrc.value = val || fallback
  }
)

const dims = computed(() => ({ width: `${props.size}px`, height: `${props.size}px` }))
</script>