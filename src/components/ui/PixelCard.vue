<template>
  <div class="pixel-card hover-lift" :class="{ 'pixel-card--clickable': clickable }" @click="clickable && $emit('click')">
    <div v-if="$slots.image" class="pixel-card__image">
      <slot name="image" />
    </div>
    <div class="pixel-card__body">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  clickable?: boolean
}>(), {
  clickable: false,
})

defineEmits<{ click: [] }>()
</script>

<style scoped>
.pixel-card {
  background: var(--color-surface);
  border: var(--border-width) solid var(--color-surface-light);
  box-shadow: var(--shadow-pixel-sm);
  overflow: hidden;
}

.pixel-card--clickable {
  cursor: pointer;
}

.pixel-card__image {
  border-bottom: var(--border-width-sm) solid var(--color-surface-light);
  overflow: hidden;
}

.pixel-card__image > :deep(img) {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
  image-rendering: pixelated;
  transition: transform 0.3s var(--ease-pixel);
}

.pixel-card:hover .pixel-card__image > :deep(img) {
  transform: scale(1.05);
}

.pixel-card__body {
  padding: var(--space-lg);
}
</style>
