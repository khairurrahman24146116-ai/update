<template>
  <div ref="el" class="reveal" :class="{ 'reveal--slow': slow }" :style="{ '--reveal-delay': `${delay}ms` }">
    <slot />
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'

const props = defineProps({
  delay: { type: Number, default: 0 },
  slow: { type: Boolean, default: false },
})

const el = ref(null)
let observer = null

onMounted(() => {
  if (!('IntersectionObserver' in window)) {
    el.value.classList.add('reveal-visible')

    return
  }
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('reveal-visible')
          observer.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.12, rootMargin: '0px 0px -32px 0px' }
  )
  observer.observe(el.value)
})

onUnmounted(() => {
  if (observer && el.value) observer.unobserve(el.value)
})
</script>

<style scoped>
.reveal {
  opacity: 0;
  transform: translateY(22px) scale(0.985);
  transition:
    opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1),
    transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
  transition-delay: var(--reveal-delay, 0ms);
}

.reveal--slow {
  transition-duration: 1.1s;
}

.reveal-visible {
  opacity: 1;
  transform: translateY(0) scale(1);
}

@media (prefers-reduced-motion: reduce) {
  .reveal {
    opacity: 1;
    transform: none;
    transition: none;
  }
}
</style>
