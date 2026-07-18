<template>
  <a :href="href" target="_blank" rel="noopener" class="social-block hover-lift">
    <AppIcon :name="icon" :size="28" class="social-block__icon" />
    <span class="social-block__label">{{ platform }}</span>
    <span class="social-block__handle">{{ handle }}</span>
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
.social-block {
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

.social-block__icon {
  color: var(--color-accent);
}

.social-block__label {
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  color: var(--color-accent);
}

.social-block__handle {
  font-family: var(--font-body);
  font-size: var(--text-sm);
  color: var(--color-text-muted);
}

.social-block:hover {
  border-color: var(--color-accent);
}

.social-block:hover .social-block__handle {
  color: var(--color-accent);
}
</style>
