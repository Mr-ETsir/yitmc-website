<template>
  <div class="qr-card block-inset">
    <h3 class="qr-card__title">{{ title }}</h3>
    <img :src="qrUrl" :alt="title" class="qr-card__image" />
    <p class="qr-card__hint">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  qrUrl: string
  hint: string
}>()
</script>

<style scoped>
.qr-card {
  padding: var(--space-xl);
  text-align: center;
  background: var(--color-surface);
}

.qr-card__title {
  font-family: var(--font-display);
  font-size: var(--text-lg);
  color: var(--color-text);
  margin-bottom: var(--space-lg);
}

.qr-card__image {
  width: 200px;
  height: 200px;
  margin: 0 auto var(--space-md);
  border: var(--border-width-sm) solid var(--color-brown);
  image-rendering: pixelated;
  object-fit: cover;
}

.qr-card__hint {
  font-size: var(--text-sm);
  color: var(--color-text-muted);
}
</style>
