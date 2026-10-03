<template>
  <a :href="href" target="_blank" rel="noopener" class="follow-card hover-lift">
    <AppIcon :name="icon" :size="28" class="follow-card__icon" />
    <span class="follow-card__label">{{ platform }}</span>
    <span class="follow-card__handle">{{ handle }}</span>
  </a>
</template>

<script setup lang="ts">
import AppIcon from '@/components/icons/AppIcon.vue'

defineProps<{
  icon: string
  platform: string
  handle: string
  href: string
}>()
</script>

<style scoped>
.follow-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-sm);
  padding: var(--space-xl);
  background: var(--color-surface);
  border: var(--border-width) solid var(--color-surface-light);
  text-decoration: none !important;
  color: var(--color-text) !important;
}

.follow-card__icon {
  color: var(--color-accent);
}

.follow-card__label {
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  color: var(--color-accent);
}

.follow-card__handle {
  font-family: var(--font-body);
  font-size: var(--text-sm);
  color: var(--color-text-muted);
}

.follow-card:hover {
  border-color: var(--color-accent);
}

.follow-card:hover .follow-card__handle {
  color: var(--color-accent);
}
</style>
