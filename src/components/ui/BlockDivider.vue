<template>
  <div class="block-divider" :class="`block-divider--${variant}`">
    <span class="block-divider__block"></span>
    <span class="block-divider__block"></span>
    <span class="block-divider__block"></span>
    <span class="block-divider__block"></span>
    <span class="block-divider__block"></span>
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  variant?: 'grass' | 'dirt' | 'stone'
}>(), {
  variant: 'grass',
})
</script>

<style scoped>
.block-divider {
  display: flex;
  height: 8px;
  width: 100%;
}

.block-divider__block {
  flex: 1;
  image-rendering: pixelated;
}

.block-divider--grass .block-divider__block:nth-child(odd) {
  background: var(--color-accent);
}
.block-divider--grass .block-divider__block:nth-child(even) {
  background: var(--color-accent-dim);
}

.block-divider--dirt .block-divider__block:nth-child(odd) {
  background: var(--color-brown);
}
.block-divider--dirt .block-divider__block:nth-child(even) {
  background: var(--color-brown-hover);
}

.block-divider--stone .block-divider__block:nth-child(odd) {
  background: var(--color-surface-light);
}
.block-divider--stone .block-divider__block:nth-child(even) {
  background: var(--color-surface);
}
</style>
