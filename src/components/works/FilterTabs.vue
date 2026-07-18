<template>
  <div class="filter-tabs">
    <button
      v-for="cat in categories"
      :key="cat.key"
      class="filter-tab"
      :class="{ 'filter-tab--active': modelValue === cat.key }"
      @click="$emit('update:modelValue', cat.key)"
    >
      {{ cat.label }}
    </button>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  categories: { key: string; label: string }[]
  modelValue: string
}>()
defineEmits<{ 'update:modelValue': [key: string] }>()
</script>

<style scoped>
.filter-tabs {
  display: flex;
  gap: var(--space-sm);
  flex-wrap: wrap;
  margin-bottom: var(--space-xl);
}

.filter-tab {
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  padding: var(--space-sm) var(--space-md);
  background: var(--color-surface);
  color: var(--color-text-muted);
  border: var(--border-width-sm) solid var(--color-surface-light);
  cursor: pointer;
  transition: all var(--duration-fast) var(--ease-pixel);
}

.filter-tab:hover {
  border-color: var(--color-brown);
  color: var(--color-text);
}

.filter-tab--active {
  background: var(--color-accent);
  color: #fff !important;
  border-color: var(--color-accent-dim);
}
</style>
