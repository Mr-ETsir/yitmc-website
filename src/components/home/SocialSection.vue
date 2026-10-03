<template>
  <section class="social">
    <div class="social__inner">
      <h2 class="social__heading" data-reveal>关注我们</h2>
      <div class="social__grid" data-reveal data-reveal-delay="1">
        <SocialBlock
          icon="music"
          platform="抖音"
          :handle="config.douyinHandle"
          :href="config.douyinUrl"
        />
        <SocialBlock
          icon="video"
          platform="B站"
          :handle="config.bilibiliHandle"
          :href="config.bilibiliUrl"
        />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import SocialBlock from '@/components/ui/SocialBlock.vue'
import { siteConfig } from '@/data'

const config = siteConfig
</script>

<style scoped>
.social__inner {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: var(--space-section) var(--space-lg);
}

.social__heading {
  font-family: var(--font-pixel);
  font-size: var(--text-xl);
  color: var(--color-accent);
  margin-bottom: var(--space-xl);
  text-align: center;
  text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.5);
}

.social__grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: var(--space-lg);
  max-width: 600px;
  margin: 0 auto;
}
</style>
