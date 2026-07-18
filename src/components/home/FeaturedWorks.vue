<template>
  <section class="featured">
    <div class="featured__inner">
      <h2 class="featured__heading">精选作品</h2>
      <div class="featured__grid stagger-children">
        <PixelCard v-for="work in featuredWorks" :key="work.id" clickable @click="$router.push('/works')">
          <template #image>
            <img :src="work.image" :alt="work.title" loading="lazy" />
          </template>
          <h3 class="featured__title">{{ work.title }}</h3>
          <p class="featured__desc">{{ work.description }}</p>
          <span class="featured__tag">{{ getCategoryLabel(work.category) }}</span>
        </PixelCard>
      </div>
      <div class="featured__more">
        <PixelButton to="/works" variant="secondary">查看全部作品 →</PixelButton>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import PixelCard from '@/components/ui/PixelCard.vue'
import PixelButton from '@/components/ui/PixelButton.vue'
import worksData from '@/data/works.json'

const router = useRouter()

const featuredWorks = worksData.works.slice(0, 6)

function getCategoryLabel(key: string): string {
  const cat = worksData.categories.find(c => c.key === key)
  return cat?.label || key
}
</script>

<style scoped>
.featured {
  background: var(--color-bg);
}

.featured__inner {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: var(--space-section) var(--space-lg);
}

.featured__heading {
  font-family: var(--font-pixel);
  font-size: var(--text-xl);
  color: var(--color-accent);
  margin-bottom: var(--space-xl);
  text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.5);
}

.featured__grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: var(--space-lg);
}

.featured__title {
  font-family: var(--font-display);
  font-size: var(--text-lg);
  color: var(--color-text);
  margin-bottom: var(--space-sm);
}

.featured__desc {
  font-size: var(--text-sm);
  color: var(--color-text-muted);
  margin-bottom: var(--space-md);
}

.featured__tag {
  display: inline-block;
  font-family: var(--font-pixel);
  font-size: 0.5rem;
  color: var(--color-accent);
  padding: var(--space-xs) var(--space-sm);
  border: var(--border-width-sm) solid var(--color-accent-dim);
  image-rendering: pixelated;
}

.featured__more {
  text-align: center;
  margin-top: var(--space-xl);
}
</style>
