<template>
  <PixelBackground>
    <AppHeader />
    <main class="news-page">
      <section class="page-hero">
        <h1 class="page-hero__title">社团动态</h1>
        <p class="page-hero__subtitle">活动公告、项目进展与荣誉成果</p>
        <BlockDivider variant="grass" />
      </section>
      <div class="news-page__inner">
        <NewsTimeline />
      </div>
    </main>
    <AppFooter />
  </PixelBackground>
</template>

<script setup lang="ts">
import PixelBackground from '@/components/layout/PixelBackground.vue'
import AppHeader from '@/components/layout/AppHeader.vue'
import AppFooter from '@/components/layout/AppFooter.vue'
import BlockDivider from '@/components/ui/BlockDivider.vue'
import NewsTimeline from '@/components/news/NewsTimeline.vue'
</script>

<style scoped>
.news-page {
  padding-top: var(--header-height);
}

.page-hero {
  text-align: center;
  padding: var(--space-3xl) var(--space-lg) 0;
}

.page-hero__title {
  font-family: var(--font-pixel);
  font-size: var(--text-2xl);
  color: var(--color-accent);
  text-shadow: 3px 3px 0px rgba(0, 0, 0, 0.5);
  margin-bottom: var(--space-sm);
}

.page-hero__subtitle {
  font-size: var(--text-base);
  color: var(--color-text-muted);
  margin-bottom: var(--space-lg);
}

.news-page__inner {
  max-width: 800px;
  margin: 0 auto;
  padding: var(--space-2xl) var(--space-lg) var(--space-section);
}
</style>
