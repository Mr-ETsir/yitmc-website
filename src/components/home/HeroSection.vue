<template>
  <section class="hero">
    <div class="hero__bg">
      <div class="hero__mosaic" ref="mosaicRef">
        <img v-for="(img, i) in mosaicImages" :key="i" :src="img" decoding="async" :style="{ animationDelay: `${i * 0.15}s` }" />
      </div>
      <div class="hero__overlay"></div>
    </div>

    <div class="hero__content gpu-layer" ref="contentRef">
      <img src="/logo/logo.png" alt="YITMC Logo" class="hero__logo animate-float" />
      <h1 class="hero__title">YITMC</h1>
      <p class="hero__subtitle">{{ config.clubName }}</p>
      <p class="hero__tagline">{{ config.clubMotto }}</p>

      <div class="hero__actions">
        <PixelButton to="/works" variant="primary"><AppIcon name="image" :size="16" /> 查看作品</PixelButton>
        <PixelButton to="/join" variant="secondary"><AppIcon name="users" :size="16" /> 加入我们</PixelButton>
      </div>
    </div>

    <div class="hero__scroll-hint">
      <AppIcon name="chevron-down" :size="24" class="hero__scroll-arrow" />
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import PixelButton from '@/components/ui/PixelButton.vue'
import AppIcon from '@/components/icons/AppIcon.vue'
import { siteConfig } from '@/data'

const config = siteConfig
const mosaicImages = [
  '/Background/复原工程/教学楼(白天)1.webp',
  '/Background/复原工程/图书馆.webp',
  '/Background/复原工程/教学楼(黄昏)1.webp',
  '/Background/复原工程/校门.webp',
  '/Background/复原工程/教学楼(晚上)1.webp',
  '/Background/复原工程/艺术楼(白天).webp',
  '/Background/复原工程/教学楼(雨天)1.webp',
  '/Background/复原工程/艺术楼(黄昏).webp',
  '/Background/其他建筑工程/竞技场.webp',
  '/Background/其他建筑工程/罗德岛.webp',
  '/Background/其他建筑工程/原神角色-丝柯克.webp',
  '/Background/社团合照/线上.webp',
]

// Subtle parallax — throttled rAF, skips sub-pixel changes
const mosaicRef = ref<HTMLElement | null>(null)
const contentRef = ref<HTMLElement | null>(null)
let rafId = 0
let lastY = 0

function onScroll() {
  if (!rafId) {
    rafId = requestAnimationFrame(() => {
      const scrollY = window.scrollY
      // Skip if scroll hasn't changed meaningfully (< 0.5px)
      if (Math.abs(scrollY - lastY) < 0.5) {
        rafId = 0
        return
      }
      lastY = scrollY

      if (mosaicRef.value) {
        mosaicRef.value.style.transform = `translate3d(0, ${Math.round(scrollY * 0.12)}px, 0)`
      }
      if (contentRef.value) {
        contentRef.value.style.transform = `translate3d(0, ${Math.round(scrollY * -0.04)}px, 0)`
      }
      rafId = 0
    })
  }
}

onMounted(() => window.addEventListener('scroll', onScroll, { passive: true }))
onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
  if (rafId) cancelAnimationFrame(rafId)
})
</script>

<style scoped>
.hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.hero__bg {
  position: absolute;
  inset: -10%; /* overshoot so parallax doesn't reveal edges */
  z-index: 0;
}

.hero__mosaic {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: repeat(3, 1fr);
  height: 120%; /* overshoot for parallax */
  opacity: 0.22;
  will-change: transform;
}

.hero__mosaic img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  will-change: opacity;
  animation: fadeIn 1.2s var(--ease-apple-out) both;
}

.hero__overlay {
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse at center, transparent 0%, var(--color-bg) 80%),
    linear-gradient(to bottom, var(--color-bg) 0%, transparent 40%, transparent 60%, var(--color-bg) 100%);
}

.hero__content {
  position: relative;
  z-index: 1;
  text-align: center;
  padding: var(--space-xl);
  will-change: transform;
}

.hero__logo {
  width: 120px;
  height: 120px;
  margin: 0 auto var(--space-lg);
  border-radius: 50%;
  object-fit: cover;
  box-shadow: var(--shadow-pixel-lg);
  will-change: transform;
}

.hero__title {
  font-family: var(--font-pixel);
  font-size: var(--text-hero);
  color: var(--color-accent);
  text-shadow:
    4px 4px 0px rgba(0, 0, 0, 0.6),
    0 0 40px rgba(92, 154, 59, 0.3);
  margin-bottom: var(--space-md);
  will-change: transform, opacity;
  animation: heroEntrance 1s var(--ease-apple-out) both;
}

.hero__subtitle {
  font-family: var(--font-display);
  font-size: var(--text-xl);
  color: var(--color-text);
  margin-bottom: var(--space-sm);
}

.hero__tagline {
  font-size: var(--text-base);
  color: var(--color-text-muted);
  margin-bottom: var(--space-xl);
}

.hero__actions {
  display: flex;
  gap: var(--space-md);
  justify-content: center;
  flex-wrap: wrap;
}

.hero__actions > * {
  animation: fadeUp 0.5s var(--ease-apple-out) both;
}
.hero__actions > *:nth-child(1) { animation-delay: 0.15s; }
.hero__actions > *:nth-child(2) { animation-delay: 0.25s; }

.hero__scroll-hint {
  position: absolute;
  bottom: var(--space-xl);
  left: 50%;
  transform: translateX(-50%);
  z-index: 1;
}

.hero__scroll-arrow {
  color: var(--color-accent);
  animation: floatIdle 2s ease-in-out infinite;
  will-change: transform;
}

@media (max-width: 768px) {
  .hero__mosaic {
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(4, 1fr);
  }
  .hero__logo {
    width: 80px;
    height: 80px;
  }
}
</style>
