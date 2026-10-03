<template>
  <section class="stats">
    <BlockDivider variant="stone" />
    <div class="stats__inner">
      <div class="stats__grid" data-reveal="zoom">
        <AnimatedCounter
          v-for="stat in statsData.stats"
          :key="stat.label"
          :target="stat.value"
          :suffix="stat.suffix"
          :label="stat.label"
        />
      </div>
    </div>
    <BlockDivider variant="stone" />
  </section>
</template>

<script setup lang="ts">
import BlockDivider from '@/components/ui/BlockDivider.vue'
import AnimatedCounter from '@/components/ui/AnimatedCounter.vue'
import { statsData } from '@/data'
</script>

<style scoped>
.stats__inner {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: var(--space-xl) var(--space-lg);
}

.stats__grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--space-md);
}

@media (max-width: 768px) {
  .stats__grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
