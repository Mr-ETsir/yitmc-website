<template>
  <PixelModal :model-value="!!work" @update:model-value="$emit('close')">
    <div v-if="work" class="work-detail">
      <img :src="work.image" :alt="work.title" class="work-detail__image" />
      <h2 class="work-detail__title">{{ work.title }}</h2>
      <p class="work-detail__desc">{{ work.description }}</p>
      <div class="work-detail__meta">
        <span class="work-detail__meta-item"><AppIcon name="user" :size="16" /> {{ work.authors?.join(', ') }}</span>
        <span class="work-detail__meta-item"><AppIcon name="calendar" :size="16" /> {{ work.date }}</span>
        <span class="work-detail__tag">{{ getCategoryLabel(work.category) }}</span>
      </div>
    </div>
  </PixelModal>
</template>

<script setup lang="ts">
import PixelModal from '@/components/ui/PixelModal.vue'
import AppIcon from '@/components/icons/AppIcon.vue'
import worksData from '@/data/works.json'

defineProps<{ work: any }>()
defineEmits<{ close: [] }>()

function getCategoryLabel(key: string): string {
  return worksData.categories.find(c => c.key === key)?.label || key
}
</script>

<style scoped>
.work-detail__image {
  width: 100%;
  max-height: 60vh;
  object-fit: contain;
  image-rendering: pixelated;
  border: var(--border-width-sm) solid var(--color-surface-light);
  margin-bottom: var(--space-lg);
}

.work-detail__title {
  font-family: var(--font-display);
  font-size: var(--text-xl);
  color: var(--color-accent);
  margin-bottom: var(--space-md);
}

.work-detail__desc {
  font-size: var(--text-base);
  color: var(--color-text-muted);
  line-height: 1.8;
  margin-bottom: var(--space-lg);
}

.work-detail__meta {
  display: flex;
  gap: var(--space-md);
  flex-wrap: wrap;
  align-items: center;
}

.work-detail__meta-item {
  font-size: var(--text-sm);
  color: var(--color-text);
}

.work-detail__tag {
  font-family: var(--font-pixel);
  font-size: 0.5rem;
  color: var(--color-accent);
  padding: var(--space-xs) var(--space-sm);
  border: var(--border-width-sm) solid var(--color-accent-dim);
}
</style>
