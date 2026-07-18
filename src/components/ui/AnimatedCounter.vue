<template>
  <div class="animated-counter">
    <span class="animated-counter__number" ref="numberEl">{{ displayValue }}</span>
    <span class="animated-counter__label">{{ label }}</span>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'

const props = withDefaults(defineProps<{
  target: number
  label: string
  duration?: number
  suffix?: string
}>(), {
  duration: 2000,
  suffix: '',
})

const numberEl = ref<HTMLElement | null>(null)
const displayValue = ref('0')

function animateCount() {
  const start = 0
  const end = props.target
  const duration = props.duration
  const startTime = performance.now()

  function step(currentTime: number) {
    const elapsed = currentTime - startTime
    const progress = Math.min(elapsed / duration, 1)
    // Stepped easing for pixel feel
    const stepped = Math.floor(progress * end)
    displayValue.value = `${stepped}${props.suffix}`
    if (progress < 1) {
      requestAnimationFrame(step)
    }
  }

  requestAnimationFrame(step)
}

onMounted(animateCount)
</script>

<style scoped>
.animated-counter {
  text-align: center;
  padding: var(--space-lg);
}

.animated-counter__number {
  display: block;
  font-family: var(--font-pixel);
  font-size: var(--text-2xl);
  color: var(--color-accent);
  text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.5);
}

.animated-counter__label {
  display: block;
  margin-top: var(--space-sm);
  font-size: var(--text-sm);
  color: var(--color-text-muted);
}
</style>
