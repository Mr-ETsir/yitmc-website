# YITMC 官网 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the official YITMC club website — Vue 3 + Vite, pixel/Minecraft theme, 6 pages, static JSON data.

**Architecture:** Vue 3 SPA with Vue Router for 6 routes. Global pixel-theme CSS tokens drive all components. GSAP handles scroll animations and page transitions. Static JSON files store all content (works, news, members, config). Components follow a layout/ui/pages split.

**Tech Stack:** Vue 3 (Composition API), Vite, Vue Router 4, Tailwind CSS, GSAP, Vitest, Playwright

---

## File Structure Map

```
src/
├── main.ts                          # App entry
├── App.vue                          # Root wrapper + page transition
├── router/index.ts                  # Vue Router config (6 routes)
├── styles/
│   ├── tokens.css                   # CSS custom properties (colors, spacing, borders)
│   ├── fonts.css                    # @font-face for Press Start 2P + ZCOOL KuaiLe
│   ├── blocks.css                   # Block texture backgrounds, border utilities
│   └── animations.css               # @keyframes for pixel animations
├── components/
│   ├── layout/
│   │   ├── AppHeader.vue            # Sticky pixel nav bar
│   │   ├── AppFooter.vue            # Footer with links
│   │   └── PixelBackground.vue      # Full-page CSS block-grid background
│   ├── ui/
│   │   ├── PixelButton.vue          # <a> or <router-link> with block border + hover
│   │   ├── PixelCard.vue            # Block-border content card
│   │   ├── PixelModal.vue           # Teleported overlay with block border
│   │   ├── BlockDivider.vue         # Decorative block-pattern horizontal rule
│   │   ├── AnimatedCounter.vue      # Animated number count-up
│   │   └── SocialBlock.vue          # Icon + label block for social media links
│   ├── home/
│   │   ├── HeroSection.vue          # Logo + title + CTA
│   │   ├── IntroSection.vue         # Club intro text
│   │   ├── FeaturedWorks.vue        # 3-4 work cards
│   │   ├── StatsSection.vue         # Animated stat counters
│   │   └── SocialSection.vue        # Douyin/Bilibili blocks
│   ├── works/
│   │   ├── FilterTabs.vue           # Category filter buttons
│   │   ├── WorkGrid.vue             # CSS grid of work cards
│   │   └── WorkDetail.vue           # Full-image modal
│   ├── news/
│   │   └── NewsTimeline.vue         # Vertical timeline
│   ├── members/
│   │   └── MemberCard.vue           # Avatar + name + role card
│   └── join/
│       ├── CopyText.vue             # Click-to-copy text block
│       └── QRCodeCard.vue           # QR image display card
├── views/
│   ├── HomeView.vue
│   ├── WorksView.vue
│   ├── NewsView.vue
│   ├── MembersView.vue
│   ├── AboutView.vue
│   └── JoinView.vue
├── data/
│   ├── works.json                   # Works array
│   ├── news.json                    # News/articles array
│   ├── members.json                 # Members array
│   ├── site-config.json             # Global config (social links, QQ, WeChat, etc.)
│   └── stats.json                   # Homepage stats numbers
└── assets/
    └── (images remain in /img at project root via public/ symlink)
```

---

### Task 1: Scaffold Vite + Vue 3 Project

**Files:**
- Create: `package.json`, `vite.config.ts`, `tsconfig.json`, `index.html`, `src/main.ts`, `src/App.vue`, `src/env.d.ts`

- [ ] **Step 1: Create package.json**

```json
{
  "name": "yitmc-website",
  "private": true,
  "version": "1.0.0",
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vue-tsc --noEmit && vite build",
    "preview": "vite preview"
  },
  "dependencies": {
    "vue": "^3.5.0",
    "vue-router": "^4.5.0",
    "gsap": "^3.12.0"
  },
  "devDependencies": {
    "@vitejs/plugin-vue": "^5.2.0",
    "typescript": "^5.7.0",
    "vite": "^6.0.0",
    "vue-tsc": "^2.2.0",
    "tailwindcss": "^4.0.0",
    "@tailwindcss/vite": "^4.0.0"
  }
}
```

- [ ] **Step 2: Run npm install**

Run: `cd /mnt/a/liu23/Documents/work/YITMC && npm install`
Expected: packages install successfully

- [ ] **Step 3: Create vite.config.ts**

```typescript
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import { resolve } from 'path'

export default defineConfig({
  plugins: [vue(), tailwindcss()],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src'),
    },
  },
  publicDir: 'public',
})
```

- [ ] **Step 4: Create tsconfig.json**

```json
{
  "compilerOptions": {
    "target": "ES2020",
    "module": "ESNext",
    "moduleResolution": "bundler",
    "strict": true,
    "jsx": "preserve",
    "resolveJsonModule": true,
    "isolatedModules": true,
    "esModuleInterop": true,
    "lib": ["ES2020", "DOM", "DOM.Iterable"],
    "skipLibCheck": true,
    "noEmit": true,
    "paths": {
      "@/*": ["./src/*"]
    },
    "baseUrl": "."
  },
  "include": ["src/**/*.ts", "src/**/*.d.ts", "src/**/*.vue"]
}
```

- [ ] **Step 5: Create index.html**

```html
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="/img/logo/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="燕京理工学院MC玩家创作协会(YITMC)官方网站 - 我的世界校园复刻工程展示与社团招新" />
    <title>YITMC - 燕京理工学院MC玩家创作协会</title>
  </head>
  <body>
    <div id="app"></div>
    <script type="module" src="/src/main.ts"></script>
  </body>
</html>
```

- [ ] **Step 6: Create src/main.ts**

```typescript
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './styles/tokens.css'
import './styles/fonts.css'
import './styles/blocks.css'
import './styles/animations.css'

const app = createApp(App)
app.use(router)
app.mount('#app')
```

- [ ] **Step 7: Create src/env.d.ts**

```typescript
/// <reference types="vite/client" />

declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}
```

- [ ] **Step 8: Create minimal src/App.vue**

```vue
<template>
  <div class="app-root">
    <router-view />
  </div>
</template>

<script setup lang="ts">
</script>
```

- [ ] **Step 9: Create public/ symlink for images**

Run: `cd /mnt/a/liu23/Documents/work/YITMC && ln -s ../img public`
Expected: symlink created, `ls public/img/logo/logo.png` works

- [ ] **Step 10: Create necessary subdirectories**

Run: `cd /mnt/a/liu23/Documents/work/YITMC && mkdir -p src/{router,styles,components/{layout,ui,home,works,news,members,join},views,data}`

- [ ] **Step 11: Verify dev server starts**

Run: `cd /mnt/a/liu23/Documents/work/YITMC && npx vite --host 0.0.0.0 &`
Expected: Vite dev server starts on localhost
Then: `kill %1`

- [ ] **Step 12: Commit**

```bash
git add -A && git commit -m "feat: scaffold Vite + Vue 3 project with Tailwind CSS"
```

---

### Task 2: Design Tokens + Global Styles

**Files:**
- Create: `src/styles/tokens.css`, `src/styles/fonts.css`, `src/styles/blocks.css`, `src/styles/animations.css`

- [ ] **Step 1: Create src/styles/tokens.css**

```css
:root {
  /* === Colors === */
  --color-bg: #1a1a1a;
  --color-surface: #2d2d2d;
  --color-surface-light: #3a3a3a;
  --color-accent: #5c9a3b;
  --color-accent-hover: #6db840;
  --color-accent-dim: #3d6b25;
  --color-brown: #c68a4b;
  --color-brown-hover: #d9a05e;
  --color-gold: #f5c842;
  --color-gold-dim: #b8961e;
  --color-text: #e8e8e8;
  --color-text-muted: #9a9a9a;
  --color-danger: #c0392b;
  --color-water: #3b82c4;

  /* === Spacing === */
  --space-xs: 4px;
  --space-sm: 8px;
  --space-md: 16px;
  --space-lg: 24px;
  --space-xl: 32px;
  --space-2xl: 48px;
  --space-3xl: 64px;
  --space-section: clamp(3rem, 2rem + 5vw, 6rem);

  /* === Borders === */
  --border-width: 4px;
  --border-width-sm: 2px;
  --radius-none: 0px;

  /* === Typography === */
  --font-pixel: 'Press Start 2P', monospace;
  --font-display: 'ZCOOL KuaiLe', sans-serif;
  --font-body: 'Courier New', 'Source Code Pro', monospace;
  --text-xs: 0.625rem;
  --text-sm: 0.75rem;
  --text-base: 1rem;
  --text-lg: 1.25rem;
  --text-xl: 1.5rem;
  --text-2xl: 2rem;
  --text-hero: clamp(2.5rem, 1rem + 7vw, 5rem);

  /* === Shadows === */
  --shadow-pixel-sm: 4px 4px 0px rgba(0, 0, 0, 0.4);
  --shadow-pixel: 6px 6px 0px rgba(0, 0, 0, 0.5);
  --shadow-pixel-lg: 8px 8px 0px rgba(0, 0, 0, 0.6);

  /* === Animation === */
  --ease-pixel: steps(4);
  --ease-pixel-smooth: steps(8);
  --duration-fast: 150ms;
  --duration-normal: 300ms;
  --duration-slow: 600ms;

  /* === Layout === */
  --max-width: 1200px;
  --header-height: 64px;
}

/* === Global Reset === */
*, *::before, *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html {
  scroll-behavior: smooth;
  background-color: var(--color-bg);
  color: var(--color-text);
  font-family: var(--font-body);
  line-height: 1.6;
  -webkit-font-smoothing: antialiased;
}

body {
  min-height: 100vh;
  overflow-x: hidden;
}

a {
  color: var(--color-accent);
  text-decoration: none;
  transition: color var(--duration-fast) var(--ease-pixel);
}

a:hover {
  color: var(--color-accent-hover);
}

h1, h2, h3, h4 {
  font-family: var(--font-display);
  font-weight: 700;
  line-height: 1.2;
}

img {
  max-width: 100%;
  display: block;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 12px;
}
::-webkit-scrollbar-track {
  background: var(--color-bg);
  border-left: var(--border-width-sm) solid var(--color-surface);
}
::-webkit-scrollbar-thumb {
  background: var(--color-surface-light);
  border: var(--border-width-sm) solid var(--color-brown);
  image-rendering: pixelated;
}
::-webkit-scrollbar-thumb:hover {
  background: var(--color-accent-dim);
}
```

- [ ] **Step 2: Create src/styles/fonts.css**

```css
/* Press Start 2P - Pixel English */
@import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

/* ZCOOL KuaiLe - Chinese display font with character */
@import url('https://fonts.googleapis.com/css2?family=ZCOOL+KuaiLe&display=swap');
```

- [ ] **Step 3: Create src/styles/blocks.css**

```css
/* Block texture background pattern */
.bg-blocks {
  background-color: var(--color-bg);
  background-image:
    linear-gradient(45deg, var(--color-surface) 1px, transparent 1px),
    linear-gradient(-45deg, var(--color-surface) 1px, transparent 1px);
  background-size: 16px 16px;
}

/* Subtle grid overlay */
.bg-grid {
  background-image:
    linear-gradient(rgba(92, 154, 59, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(92, 154, 59, 0.05) 1px, transparent 1px);
  background-size: 32px 32px;
}

/* Pixel border utility */
.border-pixel {
  border: var(--border-width) solid var(--color-brown);
  box-shadow: var(--shadow-pixel);
}

.border-pixel-sm {
  border: var(--border-width-sm) solid var(--color-brown);
  box-shadow: var(--shadow-pixel-sm);
}

/* Block edge highlight - inset lighter border */
.block-inset {
  border: var(--border-width) solid var(--color-brown);
  box-shadow:
    inset 2px 2px 0px rgba(255, 255, 255, 0.05),
    inset -2px -2px 0px rgba(0, 0, 0, 0.3),
    var(--shadow-pixel);
}

/* Pixelated corners */
.pixel-corners {
  clip-path: polygon(
    4px 0, calc(100% - 4px) 0, 100% 4px, 100% calc(100% - 4px),
    calc(100% - 4px) 100%, 4px 100%, 0 calc(100% - 4px), 0 4px
  );
}

/* Hover lift - stepped for pixel feel */
.hover-lift {
  transition: transform var(--duration-fast) var(--ease-pixel),
              box-shadow var(--duration-fast) var(--ease-pixel);
}
.hover-lift:hover {
  transform: translate(-2px, -2px);
  box-shadow: var(--shadow-pixel-lg);
}

/* Pixel text shadow */
.text-pixel-shadow {
  text-shadow: 3px 3px 0px rgba(0, 0, 0, 0.5);
}

.text-pixel-glow {
  text-shadow:
    2px 2px 0px rgba(0, 0, 0, 0.6),
    0 0 20px rgba(92, 154, 59, 0.3);
}
```

- [ ] **Step 4: Create src/styles/animations.css**

```css
/* Block appear from bottom - staggered reveal */
@keyframes blockReveal {
  0% {
    opacity: 0;
    transform: translateY(20px);
    clip-path: inset(0 0 100% 0);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
    clip-path: inset(0 0 0 0);
  }
}

/* Pixel float idle animation */
@keyframes pixelFloat {
  0%, 100% { transform: translateY(0); }
  25% { transform: translateY(-4px); }
  75% { transform: translateY(-2px); }
}

/* Hero title glitch effect */
@keyframes pixelGlitch {
  0%, 100% { transform: translate(0); }
  20% { transform: translate(-2px, 2px); }
  40% { transform: translate(-2px, -2px); }
  60% { transform: translate(2px, 2px); }
  80% { transform: translate(2px, -2px); }
}

/* Block stacking loader */
@keyframes blockStack {
  0% { transform: translateY(100%) scale(0.8); opacity: 0; }
  50% { opacity: 1; }
  100% { transform: translateY(0) scale(1); opacity: 1; }
}

/* Fade in up */
@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(30px); }
  100% { opacity: 1; transform: translateY(0); }
}

/* Border pulse for interactive elements */
@keyframes borderPulse {
  0%, 100% { border-color: var(--color-brown); }
  50% { border-color: var(--color-accent); }
}

/* Grass block stripes background scroll */
@keyframes grassScroll {
  0% { background-position: 0 0; }
  100% { background-position: 32px 0; }
}

/* Utility classes */
.animate-reveal {
  animation: blockReveal 0.6s var(--ease-pixel) both;
}

.animate-float {
  animation: pixelFloat 3s ease-in-out infinite;
}

.animate-fade-in-up {
  animation: fadeInUp 0.5s var(--ease-pixel) both;
}

/* Stagger children */
.stagger-children > * {
  opacity: 0;
  animation: blockReveal 0.6s var(--ease-pixel) forwards;
}
.stagger-children > *:nth-child(1) { animation-delay: 0.1s; }
.stagger-children > *:nth-child(2) { animation-delay: 0.2s; }
.stagger-children > *:nth-child(3) { animation-delay: 0.3s; }
.stagger-children > *:nth-child(4) { animation-delay: 0.4s; }
.stagger-children > *:nth-child(5) { animation-delay: 0.5s; }
.stagger-children > *:nth-child(6) { animation-delay: 0.6s; }
.stagger-children > *:nth-child(7) { animation-delay: 0.7s; }
.stagger-children > *:nth-child(8) { animation-delay: 0.8s; }

/* Page transition */
.page-enter-active {
  animation: blockReveal 0.4s var(--ease-pixel);
}
.page-leave-active {
  animation: blockReveal 0.3s var(--ease-pixel) reverse;
}
```

- [ ] **Step 5: Commit**

```bash
git add src/styles/ && git commit -m "feat: add design tokens, fonts, block utilities, and animations"
```

---

### Task 3: PixelBackground Component

**Files:**
- Create: `src/components/layout/PixelBackground.vue`

- [ ] **Step 1: Create PixelBackground.vue**

```vue
<template>
  <div class="pixel-bg">
    <div class="pixel-bg__grid"></div>
    <div class="pixel-bg__noise"></div>
    <slot />
  </div>
</template>

<script setup lang="ts">
</script>

<style scoped>
.pixel-bg {
  position: relative;
  min-height: 100vh;
  background-color: var(--color-bg);
  overflow: hidden;
}

.pixel-bg__grid {
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  background-image:
    linear-gradient(rgba(92, 154, 59, 0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(92, 154, 59, 0.04) 1px, transparent 1px);
  background-size: 32px 32px;
}

.pixel-bg__noise {
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  opacity: 0.03;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
}

.pixel-bg > :not(.pixel-bg__grid):not(.pixel-bg__noise) {
  position: relative;
  z-index: 1;
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add src/components/layout/PixelBackground.vue && git commit -m "feat: add PixelBackground component"
```

---

### Task 4: AppHeader Component

**Files:**
- Create: `src/components/layout/AppHeader.vue`

- [ ] **Step 1: Create AppHeader.vue**

```vue
<template>
  <header class="app-header" :class="{ 'app-header--scrolled': scrolled }">
    <div class="app-header__inner">
      <router-link to="/" class="app-header__logo">
        <img src="/img/logo/logo.png" alt="YITMC Logo" class="app-header__logo-img" />
        <span class="app-header__logo-text">YITMC</span>
      </router-link>

      <nav class="app-header__nav" :class="{ 'app-header__nav--open': menuOpen }">
        <router-link v-for="link in navLinks" :key="link.to" :to="link.to" class="app-header__link">
          {{ link.label }}
        </router-link>
      </nav>

      <button class="app-header__burger" @click="menuOpen = !menuOpen" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const menuOpen = ref(false)
const scrolled = ref(false)

const navLinks = [
  { to: '/', label: '首页' },
  { to: '/works', label: '作品' },
  { to: '/news', label: '动态' },
  { to: '/members', label: '成员' },
  { to: '/about', label: '关于' },
  { to: '/join', label: '加入' },
]

function onScroll() {
  scrolled.value = window.scrollY > 20
}

onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<style scoped>
.app-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 100;
  height: var(--header-height);
  background: var(--color-bg);
  border-bottom: var(--border-width) solid var(--color-surface);
  transition: background var(--duration-fast) var(--ease-pixel),
              border-color var(--duration-fast) var(--ease-pixel);
}

.app-header--scrolled {
  background: rgba(26, 26, 26, 0.95);
  border-bottom-color: var(--color-accent-dim);
}

.app-header__inner {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: 0 var(--space-lg);
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.app-header__logo {
  display: flex;
  align-items: center;
  gap: var(--space-sm);
  color: var(--color-text) !important;
  text-decoration: none;
}

.app-header__logo-img {
  width: 40px;
  height: 40px;
  image-rendering: pixelated;
  border: 2px solid var(--color-brown);
}

.app-header__logo-text {
  font-family: var(--font-pixel);
  font-size: var(--text-lg);
  color: var(--color-accent);
  text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.5);
}

.app-header__nav {
  display: flex;
  gap: var(--space-xs);
}

.app-header__link {
  font-family: var(--font-display);
  font-size: var(--text-sm);
  color: var(--color-text-muted) !important;
  padding: var(--space-xs) var(--space-md);
  border: var(--border-width-sm) solid transparent;
  transition: all var(--duration-fast) var(--ease-pixel);
  image-rendering: pixelated;
}

.app-header__link:hover,
.app-header__link.router-link-active {
  color: var(--color-accent) !important;
  border-color: var(--color-accent);
  background: rgba(92, 154, 59, 0.1);
}

.app-header__burger {
  display: none;
  flex-direction: column;
  gap: 4px;
  background: none;
  border: var(--border-width-sm) solid var(--color-brown);
  padding: 8px;
  cursor: pointer;
}

.app-header__burger span {
  display: block;
  width: 24px;
  height: 3px;
  background: var(--color-text);
}

@media (max-width: 768px) {
  .app-header__nav {
    position: fixed;
    top: var(--header-height);
    left: 0;
    right: 0;
    flex-direction: column;
    background: var(--color-bg);
    border-bottom: var(--border-width) solid var(--color-accent-dim);
    padding: var(--space-md);
    display: none;
  }

  .app-header__nav--open {
    display: flex;
  }

  .app-header__burger {
    display: flex;
  }
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add src/components/layout/AppHeader.vue && git commit -m "feat: add AppHeader with responsive nav"
```

---

### Task 5: AppFooter Component

**Files:**
- Create: `src/components/layout/AppFooter.vue`

- [ ] **Step 1: Create AppFooter.vue**

```vue
<template>
  <footer class="app-footer">
    <BlockDivider variant="dirt" />
    <div class="app-footer__inner">
      <div class="app-footer__brand">
        <img src="/img/logo/logo.png" alt="YITMC" class="app-footer__logo" />
        <p class="app-footer__name">燕京理工学院 MC玩家创作协会</p>
        <p class="app-footer__motto">用方块还原校园，用创意连接世界</p>
      </div>

      <div class="app-footer__links">
        <h4 class="app-footer__heading">快速导航</h4>
        <router-link v-for="link in navLinks" :key="link.to" :to="link.to" class="app-footer__link">
          {{ link.label }}
        </router-link>
      </div>

      <div class="app-footer__social">
        <h4 class="app-footer__heading">关注我们</h4>
        <a :href="config.douyinUrl" target="_blank" rel="noopener" class="app-footer__social-link">🎵 抖音</a>
        <a :href="config.bilibiliUrl" target="_blank" rel="noopener" class="app-footer__social-link">📺 B站</a>
      </div>
    </div>

    <div class="app-footer__bottom">
      <p>&copy; {{ year }} YITMC. 燕京理工学院官方注册社团.</p>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import BlockDivider from '@/components/ui/BlockDivider.vue'
import siteConfig from '@/data/site-config.json'

const config = siteConfig
const year = computed(() => new Date().getFullYear())

const navLinks = [
  { to: '/', label: '首页' },
  { to: '/works', label: '作品展示' },
  { to: '/news', label: '社团动态' },
  { to: '/members', label: '成员风采' },
  { to: '/about', label: '关于我们' },
  { to: '/join', label: '加入我们' },
]
</script>

<style scoped>
.app-footer {
  background: var(--color-surface);
  border-top: var(--border-width) solid var(--color-brown);
}

.app-footer__inner {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: var(--space-2xl) var(--space-lg);
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: var(--space-xl);
}

.app-footer__logo {
  width: 48px;
  height: 48px;
  image-rendering: pixelated;
  border: var(--border-width-sm) solid var(--color-brown);
  margin-bottom: var(--space-sm);
}

.app-footer__name {
  font-family: var(--font-display);
  font-size: var(--text-lg);
  color: var(--color-text);
}

.app-footer__motto {
  font-size: var(--text-sm);
  color: var(--color-text-muted);
  margin-top: var(--space-xs);
}

.app-footer__heading {
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  color: var(--color-accent);
  margin-bottom: var(--space-md);
  text-transform: uppercase;
}

.app-footer__link,
.app-footer__social-link {
  display: block;
  font-size: var(--text-sm);
  color: var(--color-text-muted) !important;
  padding: var(--space-xs) 0;
  transition: color var(--duration-fast) var(--ease-pixel);
}

.app-footer__link:hover,
.app-footer__social-link:hover {
  color: var(--color-accent) !important;
}

.app-footer__bottom {
  border-top: var(--border-width-sm) solid var(--color-surface-light);
  padding: var(--space-md) var(--space-lg);
  text-align: center;
  font-size: var(--text-xs);
  color: var(--color-text-muted);
}

@media (max-width: 768px) {
  .app-footer__inner {
    grid-template-columns: 1fr;
    text-align: center;
  }
  .app-footer__logo {
    margin: 0 auto var(--space-sm);
  }
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add src/components/layout/AppFooter.vue && git commit -m "feat: add AppFooter with navigation and social links"
```

---

### Task 6: Base UI Components (PixelButton, PixelCard, BlockDivider)

**Files:**
- Create: `src/components/ui/PixelButton.vue`, `src/components/ui/PixelCard.vue`, `src/components/ui/BlockDivider.vue`

- [ ] **Step 1: Create PixelButton.vue**

```vue
<template>
  <component
    :is="to ? 'router-link' : href ? 'a' : 'button'"
    :to="to"
    :href="href"
    :target="href ? '_blank' : undefined"
    :rel="href ? 'noopener' : undefined"
    class="pixel-btn"
    :class="[`pixel-btn--${variant}`, { 'pixel-btn--block': block }]"
  >
    <slot />
  </component>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  to?: string
  href?: string
  variant?: 'primary' | 'secondary' | 'gold'
  block?: boolean
}>(), {
  variant: 'primary',
  block: false,
})
</script>

<style scoped>
.pixel-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-sm);
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  padding: var(--space-md) var(--space-xl);
  border: var(--border-width) solid;
  cursor: pointer;
  text-decoration: none !important;
  transition: all var(--duration-fast) var(--ease-pixel);
  image-rendering: pixelated;
  user-select: none;
}

.pixel-btn--block {
  display: flex;
  width: 100%;
}

.pixel-btn--primary {
  background: var(--color-accent);
  color: #fff !important;
  border-color: var(--color-accent-dim);
  box-shadow: var(--shadow-pixel);
}

.pixel-btn--primary:hover {
  background: var(--color-accent-hover);
  transform: translate(-2px, -2px);
  box-shadow: var(--shadow-pixel-lg);
}

.pixel-btn--secondary {
  background: var(--color-surface);
  color: var(--color-text) !important;
  border-color: var(--color-brown);
  box-shadow: var(--shadow-pixel-sm);
}

.pixel-btn--secondary:hover {
  border-color: var(--color-accent);
  color: var(--color-accent) !important;
  transform: translate(-2px, -2px);
  box-shadow: var(--shadow-pixel);
}

.pixel-btn--gold {
  background: var(--color-gold);
  color: #1a1a1a !important;
  border-color: var(--color-gold-dim);
  box-shadow: var(--shadow-pixel);
}

.pixel-btn--gold:hover {
  filter: brightness(1.1);
  transform: translate(-2px, -2px);
  box-shadow: var(--shadow-pixel-lg);
}

.pixel-btn:active {
  transform: translate(0, 0) !important;
  box-shadow: none !important;
}
</style>
```

- [ ] **Step 2: Create PixelCard.vue**

```vue
<template>
  <div class="pixel-card hover-lift" :class="{ 'pixel-card--clickable': clickable }" @click="clickable && $emit('click')">
    <div v-if="$slots.image" class="pixel-card__image">
      <slot name="image" />
    </div>
    <div class="pixel-card__body">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  clickable?: boolean
}>(), {
  clickable: false,
})

defineEmits<{ click: [] }>()
</script>

<style scoped>
.pixel-card {
  background: var(--color-surface);
  border: var(--border-width) solid var(--color-surface-light);
  box-shadow: var(--shadow-pixel-sm);
  overflow: hidden;
}

.pixel-card--clickable {
  cursor: pointer;
}

.pixel-card__image {
  border-bottom: var(--border-width-sm) solid var(--color-surface-light);
  overflow: hidden;
}

.pixel-card__image > :deep(img) {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
  image-rendering: pixelated;
  transition: transform 0.3s var(--ease-pixel);
}

.pixel-card:hover .pixel-card__image > :deep(img) {
  transform: scale(1.05);
}

.pixel-card__body {
  padding: var(--space-lg);
}
</style>
```

- [ ] **Step 3: Create BlockDivider.vue**

```vue
<template>
  <div class="block-divider" :class="`block-divider--${variant}`">
    <span class="block-divider__block"></span>
    <span class="block-divider__block"></span>
    <span class="block-divider__block"></span>
    <span class="block-divider__block"></span>
    <span class="block-divider__block"></span>
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  variant?: 'grass' | 'dirt' | 'stone'
}>(), {
  variant: 'grass',
})
</script>

<style scoped>
.block-divider {
  display: flex;
  height: 8px;
  width: 100%;
}

.block-divider__block {
  flex: 1;
  image-rendering: pixelated;
}

.block-divider--grass .block-divider__block:nth-child(odd) {
  background: var(--color-accent);
}
.block-divider--grass .block-divider__block:nth-child(even) {
  background: var(--color-accent-dim);
}

.block-divider--dirt .block-divider__block:nth-child(odd) {
  background: var(--color-brown);
}
.block-divider--dirt .block-divider__block:nth-child(even) {
  background: var(--color-brown-hover);
}

.block-divider--stone .block-divider__block:nth-child(odd) {
  background: var(--color-surface-light);
}
.block-divider--stone .block-divider__block:nth-child(even) {
  background: var(--color-surface);
}
</style>
```

- [ ] **Step 4: Commit**

```bash
git add src/components/ui/PixelButton.vue src/components/ui/PixelCard.vue src/components/ui/BlockDivider.vue && git commit -m "feat: add PixelButton, PixelCard, BlockDivider components"
```

---

### Task 7: More UI Components (PixelModal, SocialBlock, AnimatedCounter)

**Files:**
- Create: `src/components/ui/PixelModal.vue`, `src/components/ui/SocialBlock.vue`, `src/components/ui/AnimatedCounter.vue`

- [ ] **Step 1: Create PixelModal.vue**

```vue
<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="pixel-modal__overlay" @click.self="$emit('update:modelValue', false)">
        <div class="pixel-modal">
          <button class="pixel-modal__close" @click="$emit('update:modelValue', false)" aria-label="Close">&times;</button>
          <div class="pixel-modal__body">
            <slot />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
defineProps<{ modelValue: boolean }>()
defineEmits<{ 'update:modelValue': [value: boolean] }>()
</script>

<style scoped>
.pixel-modal__overlay {
  position: fixed;
  inset: 0;
  z-index: 200;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: var(--space-lg);
}

.pixel-modal {
  position: relative;
  max-width: 800px;
  max-height: 90vh;
  width: 100%;
  background: var(--color-surface);
  border: var(--border-width) solid var(--color-brown);
  box-shadow: var(--shadow-pixel-lg);
  overflow-y: auto;
}

.pixel-modal__close {
  position: absolute;
  top: var(--space-sm);
  right: var(--space-sm);
  z-index: 10;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--color-danger);
  color: #fff;
  border: var(--border-width-sm) solid rgba(0, 0, 0, 0.3);
  font-size: var(--text-xl);
  cursor: pointer;
  font-family: var(--font-pixel);
}

.pixel-modal__body {
  padding: var(--space-lg);
}

/* Transition */
.modal-enter-active { animation: blockReveal 0.3s var(--ease-pixel); }
.modal-leave-active { animation: blockReveal 0.2s var(--ease-pixel) reverse; }
</style>
```

- [ ] **Step 2: Create SocialBlock.vue**

```vue
<template>
  <a :href="href" target="_blank" rel="noopener" class="social-block hover-lift">
    <span class="social-block__icon">{{ icon }}</span>
    <span class="social-block__label">{{ platform }}</span>
    <span class="social-block__handle">{{ handle }}</span>
  </a>
</template>

<script setup lang="ts">
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
  font-size: var(--text-2xl);
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
```

- [ ] **Step 3: Create AnimatedCounter.vue**

```vue
<template>
  <div class="animated-counter">
    <span class="animated-counter__number" ref="numberEl">{{ displayValue }}</span>
    <span class="animated-counter__label">{{ label }}</span>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'

const props = withDefaults(defineProps<{
  target: number
  label: string
  duration?: number
  suffix?: string
}>(), {
  duration: 2000,
  suffix: '',
})

const numberEl = ref<HTMLElement | null>(null)
const displayValue = ref('0')

function animateCount() {
  const start = 0
  const end = props.target
  const duration = props.duration
  const startTime = performance.now()

  function step(currentTime: number) {
    const elapsed = currentTime - startTime
    const progress = Math.min(elapsed / duration, 1)
    // Stepped easing for pixel feel
    const stepped = Math.floor(progress * end)
    displayValue.value = `${stepped}${props.suffix}`
    if (progress < 1) {
      requestAnimationFrame(step)
    }
  }

  requestAnimationFrame(step)
}

onMounted(animateCount)
</script>

<style scoped>
.animated-counter {
  text-align: center;
  padding: var(--space-lg);
}

.animated-counter__number {
  display: block;
  font-family: var(--font-pixel);
  font-size: var(--text-2xl);
  color: var(--color-accent);
  text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.5);
}

.animated-counter__label {
  display: block;
  margin-top: var(--space-sm);
  font-size: var(--text-sm);
  color: var(--color-text-muted);
}
</style>
```

- [ ] **Step 4: Commit**

```bash
git add src/components/ui/PixelModal.vue src/components/ui/SocialBlock.vue src/components/ui/AnimatedCounter.vue && git commit -m "feat: add PixelModal, SocialBlock, AnimatedCounter components"
```

---

### Task 8: JSON Data Files

**Files:**
- Create: `src/data/site-config.json`, `src/data/works.json`, `src/data/news.json`, `src/data/members.json`, `src/data/stats.json`

- [ ] **Step 1: Create src/data/site-config.json**

```json
{
  "clubName": "燕京理工学院MC玩家创作协会",
  "clubNameEn": "YITMC",
  "clubMotto": "用方块还原校园，用创意连接世界",
  "schoolName": "燕京理工学院",
  "registrationInfo": "燕京理工学院官方注册学生社团",
  "douyinUrl": "https://v.douyin.com/",
  "douyinHandle": "@YITMC",
  "bilibiliUrl": "https://space.bilibili.com/",
  "bilibiliHandle": "@YITMC",
  "qqGroup": "123456789",
  "qqGroupJoinUrl": "https://qm.qq.com/q/xxxxxx",
  "wechatGroupQrUrl": "/img/Background/20.jpg",
  "email": "yitmc@example.com"
}
```

- [ ] **Step 2: Create src/data/stats.json**

```json
{
  "stats": [
    { "value": 50, "suffix": "+", "label": "社团成员" },
    { "value": 20, "suffix": "+", "label": "完成项目" },
    { "value": 15, "suffix": "+", "label": "活动次数" },
    { "value": 3, "suffix": "年", "label": "建社时间" }
  ]
}
```

- [ ] **Step 3: Create src/data/works.json**

```json
{
  "categories": [
    { "key": "all", "label": "全部" },
    { "key": "architecture", "label": "建筑" },
    { "key": "landscape", "label": "景观" },
    { "key": "campus", "label": "校园复刻" },
    { "key": "event", "label": "活动作品" }
  ],
  "works": [
    {
      "id": 1,
      "title": "燕京理工学院主教学楼",
      "category": "campus",
      "image": "/img/Background/1.png",
      "description": "1:1复刻学校主教学楼外观，精确还原建筑细节与周围环境",
      "authors": ["YITMC建筑组"],
      "date": "2025-06"
    },
    {
      "id": 2,
      "title": "校园图书馆",
      "category": "campus",
      "image": "/img/Background/2.png",
      "description": "还原学校图书馆建筑群，包括阅览室与周边景观",
      "authors": ["YITMC建筑组"],
      "date": "2025-09"
    },
    {
      "id": 3,
      "title": "行政楼广场",
      "category": "landscape",
      "image": "/img/Background/3.png",
      "description": "校园中心广场及行政楼区域完整复刻，展现校园景观规划",
      "authors": ["YITMC景观组"],
      "date": "2025-09"
    },
    {
      "id": 4,
      "title": "宿舍区建筑群",
      "category": "architecture",
      "image": "/img/Background/4.png",
      "description": "学生宿舍楼群复刻，包括周边道路与绿化",
      "authors": ["YITMC建筑组"],
      "date": "2025-05"
    },
    {
      "id": 5,
      "title": "体育场景观",
      "category": "landscape",
      "image": "/img/Background/5.png",
      "description": "学校体育场及运动设施区域的完整复刻",
      "authors": ["YITMC景观组"],
      "date": "2025-05"
    },
    {
      "id": 6,
      "title": "餐厅建筑复刻",
      "category": "architecture",
      "image": "/img/Background/6.png",
      "description": "学校餐厅建筑外观及周边环境的Minecraft复刻",
      "authors": ["YITMC建筑组"],
      "date": "2025-05"
    }
  ]
}
```

- [ ] **Step 4: Create src/data/news.json**

```json
{
  "news": [
    {
      "id": 1,
      "title": "YITMC 2026秋季招新正式启动！",
      "date": "2026-09-15",
      "type": "announcement",
      "summary": "欢迎2026级新生加入燕京理工学院MC玩家创作协会，一起用方块创造无限可能！",
      "content": "详细招新信息请查看加入我们页面，或加入QQ群咨询。"
    },
    {
      "id": 2,
      "title": "校园复刻工程二期正式完工",
      "date": "2026-03-20",
      "type": "project",
      "summary": "经过三个月的努力，YITMC成功完成了校园图书馆与行政楼区域的完整复刻。",
      "content": "二期工程覆盖了图书馆、行政楼及周边广场区域，建筑细节精确到每扇窗户和每级台阶。"
    },
    {
      "id": 3,
      "title": "YITMC在2025高校MC建筑大赛中获奖",
      "date": "2025-12-01",
      "type": "achievement",
      "summary": "我社成员在全国高校MC建筑创作大赛中荣获最佳校园复刻奖。",
      "content": "参赛作品为燕京理工学院校园一期复刻工程，获得了评委组的一致好评。"
    },
    {
      "id": 4,
      "title": "社团文化节MC建筑作品展圆满举办",
      "date": "2025-10-10",
      "type": "event",
      "summary": "在校园社团文化节期间，YITMC举办了MC建筑作品展览，吸引了大量同学参观。",
      "content": "展览现场展示了社团成立以来的优秀建筑作品，并在大屏幕上进行了MC实机演示。"
    }
  ]
}
```

- [ ] **Step 5: Create src/data/members.json**

```json
{
  "leadership": [
    {
      "id": 1,
      "name": "社长",
      "role": "社长",
      "avatar": "",
      "description": "统筹社团全面工作，组织社团发展规划",
      "grade": "2024级"
    },
    {
      "id": 2,
      "name": "副社长",
      "role": "副社长",
      "avatar": "",
      "description": "协助社长管理社团，分管建筑组与活动组织",
      "grade": "2024级"
    },
    {
      "id": 3,
      "name": "技术组长",
      "role": "技术组长",
      "avatar": "",
      "description": "负责服务器运维、模组开发与技术支持",
      "grade": "2023级"
    },
    {
      "id": 4,
      "name": "宣传组长",
      "role": "宣传组长",
      "avatar": "",
      "description": "负责社团宣传，运营抖音号与B站号",
      "grade": "2024级"
    }
  ]
}
```

- [ ] **Step 6: Commit**

```bash
git add src/data/ && git commit -m "feat: add static data files for all pages"
```

---

### Task 9: Router Setup

**Files:**
- Create: `src/router/index.ts`

- [ ] **Step 1: Create src/router/index.ts**

```typescript
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/HomeView.vue'),
      meta: { title: 'YITMC - 首页' },
    },
    {
      path: '/works',
      name: 'works',
      component: () => import('@/views/WorksView.vue'),
      meta: { title: '作品展示 - YITMC' },
    },
    {
      path: '/news',
      name: 'news',
      component: () => import('@/views/NewsView.vue'),
      meta: { title: '社团动态 - YITMC' },
    },
    {
      path: '/members',
      name: 'members',
      component: () => import('@/views/MembersView.vue'),
      meta: { title: '成员风采 - YITMC' },
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('@/views/AboutView.vue'),
      meta: { title: '关于我们 - YITMC' },
    },
    {
      path: '/join',
      name: 'join',
      component: () => import('@/views/JoinView.vue'),
      meta: { title: '加入我们 - YITMC' },
    },
  ],
  scrollBehavior() {
    return { top: 0 }
  },
})

router.afterEach((to) => {
  document.title = (to.meta.title as string) || 'YITMC'
})

export default router
```

- [ ] **Step 2: Commit**

```bash
git add src/router/index.ts && git commit -m "feat: add Vue Router with lazy-loaded routes"
```

---

### Task 10: HomeView — Hero & Intro Sections

**Files:**
- Create: `src/components/home/HeroSection.vue`, `src/components/home/IntroSection.vue`, `src/views/HomeView.vue`

- [ ] **Step 1: Create HeroSection.vue**

```vue
<template>
  <section class="hero">
    <div class="hero__bg">
      <div class="hero__mosaic">
        <img v-for="(img, i) in mosaicImages" :key="i" :src="img" :style="{ animationDelay: `${i * 0.3}s` }" />
      </div>
      <div class="hero__overlay"></div>
    </div>

    <div class="hero__content">
      <img src="/img/logo/logo.png" alt="YITMC Logo" class="hero__logo animate-float" />
      <h1 class="hero__title">YITMC</h1>
      <p class="hero__subtitle">{{ config.clubName }}</p>
      <p class="hero__tagline">{{ config.clubMotto }}</p>

      <div class="hero__actions">
        <PixelButton to="/works" variant="primary">🖼️ 查看作品</PixelButton>
        <PixelButton to="/join" variant="secondary">🤝 加入我们</PixelButton>
      </div>
    </div>

    <div class="hero__scroll-hint">
      <span class="hero__scroll-arrow">▼</span>
    </div>
  </section>
</template>

<script setup lang="ts">
import PixelButton from '@/components/ui/PixelButton.vue'
import siteConfig from '@/data/site-config.json'

const config = siteConfig
const mosaicImages = [
  '/img/Background/1.png', '/img/Background/2.png', '/img/Background/3.png',
  '/img/Background/4.png', '/img/Background/5.png', '/img/Background/6.png',
  '/img/Background/8.png', '/img/Background/9.png',
]
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
  inset: 0;
  z-index: 0;
}

.hero__mosaic {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: repeat(2, 1fr);
  height: 100%;
  opacity: 0.3;
}

.hero__mosaic img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  image-rendering: pixelated;
  animation: blockReveal 0.8s var(--ease-pixel) both;
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
}

.hero__logo {
  width: 120px;
  height: 120px;
  margin: 0 auto var(--space-lg);
  image-rendering: pixelated;
  border: var(--border-width) solid var(--color-brown);
  box-shadow: var(--shadow-pixel-lg);
  background: var(--color-surface);
}

.hero__title {
  font-family: var(--font-pixel);
  font-size: var(--text-hero);
  color: var(--color-accent);
  text-shadow:
    4px 4px 0px rgba(0, 0, 0, 0.6),
    0 0 40px rgba(92, 154, 59, 0.3);
  margin-bottom: var(--space-md);
  animation: pixelGlitch 4s var(--ease-pixel) infinite;
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

.hero__scroll-hint {
  position: absolute;
  bottom: var(--space-xl);
  left: 50%;
  transform: translateX(-50%);
  z-index: 1;
}

.hero__scroll-arrow {
  font-size: var(--text-xl);
  color: var(--color-accent);
  animation: pixelFloat 2s ease-in-out infinite;
}

@media (max-width: 768px) {
  .hero__mosaic {
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(4, 1fr);
  }
  .hero__logo {
    width: 80px;
    height: 80px;
  }
}
</style>
```

- [ ] **Step 2: Create IntroSection.vue**

```vue
<template>
  <section class="intro">
    <BlockDivider variant="grass" />
    <div class="intro__inner">
      <h2 class="intro__heading">关于 YITMC</h2>
      <div class="intro__grid">
        <div class="intro__text">
          <p>YITMC（燕京理工学院MC玩家创作协会）是燕京理工学院<b>官方注册</b>的学生社团组织。</p>
          <p>我们是一群热爱Minecraft与建筑创作的大学生。社团以"用方块还原校园，用创意连接世界"为宗旨，致力于通过Minecraft这款游戏，展现校园建筑之美，培养成员的团队协作与创造力。</p>
          <p>社团核心项目——<b>燕京理工学院校园复刻工程</b>，旨在1:1精确还原学校建筑群与景观。目前已完成多期工程，作品在抖音与B站等平台持续更新。</p>
        </div>
        <div class="intro__badge block-inset">
          <span class="intro__badge-icon">🏛️</span>
          <span class="intro__badge-text">学校官方注册社团</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import BlockDivider from '@/components/ui/BlockDivider.vue'
</script>

<style scoped>
.intro__inner {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: var(--space-section) var(--space-lg);
}

.intro__heading {
  font-family: var(--font-pixel);
  font-size: var(--text-xl);
  color: var(--color-accent);
  margin-bottom: var(--space-xl);
  text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.5);
}

.intro__grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: var(--space-xl);
  align-items: center;
}

.intro__text {
  font-size: var(--text-base);
  color: var(--color-text-muted);
  line-height: 1.8;
}

.intro__text p {
  margin-bottom: var(--space-md);
}

.intro__text b {
  color: var(--color-accent);
}

.intro__badge {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-md);
  padding: var(--space-2xl) var(--space-xl);
  text-align: center;
  background: var(--color-surface);
}

.intro__badge-icon {
  font-size: 3rem;
}

.intro__badge-text {
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  color: var(--color-gold);
  text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5);
}

@media (max-width: 768px) {
  .intro__grid {
    grid-template-columns: 1fr;
  }
}
</style>
```

- [ ] **Step 3: Create HomeView.vue (temporary, will be extended)**

```vue
<template>
  <PixelBackground>
    <AppHeader />
    <main>
      <HeroSection />
      <IntroSection />
    </main>
    <AppFooter />
  </PixelBackground>
</template>

<script setup lang="ts">
import PixelBackground from '@/components/layout/PixelBackground.vue'
import AppHeader from '@/components/layout/AppHeader.vue'
import AppFooter from '@/components/layout/AppFooter.vue'
import HeroSection from '@/components/home/HeroSection.vue'
import IntroSection from '@/components/home/IntroSection.vue'
</script>
```

- [ ] **Step 4: Commit**

```bash
git add src/components/home/HeroSection.vue src/components/home/IntroSection.vue src/views/HomeView.vue && git commit -m "feat: add HomeView with Hero and Intro sections"
```

---

### Task 11: HomeView — Remaining Sections & Update App.vue

**Files:**
- Create: `src/components/home/FeaturedWorks.vue`, `src/components/home/StatsSection.vue`, `src/components/home/SocialSection.vue`
- Modify: `src/views/HomeView.vue`, `src/App.vue`

- [ ] **Step 1: Create FeaturedWorks.vue**

```vue
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

const featuredWorks = worksData.works.slice(0, 4)

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
```

- [ ] **Step 2: Create StatsSection.vue**

```vue
<template>
  <section class="stats">
    <BlockDivider variant="stone" />
    <div class="stats__inner">
      <div class="stats__grid">
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
import statsData from '@/data/stats.json'
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
```

- [ ] **Step 3: Create SocialSection.vue**

```vue
<template>
  <section class="social">
    <div class="social__inner">
      <h2 class="social__heading">关注我们</h2>
      <div class="social__grid">
        <SocialBlock
          icon="🎵"
          platform="抖音"
          :handle="config.douyinHandle"
          :href="config.douyinUrl"
        />
        <SocialBlock
          icon="📺"
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
import siteConfig from '@/data/site-config.json'

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
```

- [ ] **Step 4: Update HomeView.vue to include all sections**

Edit `src/views/HomeView.vue` — add FeaturedWorks, StatsSection, SocialSection imports and usage inside `<main>`:

```vue
<template>
  <PixelBackground>
    <AppHeader />
    <main>
      <HeroSection />
      <IntroSection />
      <FeaturedWorks />
      <StatsSection />
      <SocialSection />
    </main>
    <AppFooter />
  </PixelBackground>
</template>

<script setup lang="ts">
import PixelBackground from '@/components/layout/PixelBackground.vue'
import AppHeader from '@/components/layout/AppHeader.vue'
import AppFooter from '@/components/layout/AppFooter.vue'
import HeroSection from '@/components/home/HeroSection.vue'
import IntroSection from '@/components/home/IntroSection.vue'
import FeaturedWorks from '@/components/home/FeaturedWorks.vue'
import StatsSection from '@/components/home/StatsSection.vue'
import SocialSection from '@/components/home/SocialSection.vue'
</script>
```

- [ ] **Step 5: Update App.vue with page transition**

```vue
<template>
  <div class="app-root">
    <router-view v-slot="{ Component }">
      <transition name="page" mode="out-in">
        <component :is="Component" />
      </transition>
    </router-view>
  </div>
</template>

<script setup lang="ts">
</script>

<style>
.app-root {
  min-height: 100vh;
  background: var(--color-bg);
}
</style>
```

- [ ] **Step 6: Commit**

```bash
git add src/components/home/ src/views/HomeView.vue src/App.vue && git commit -m "feat: complete HomeView sections and add page transitions"
```

---

### Task 12: WorksView + Components

**Files:**
- Create: `src/components/works/FilterTabs.vue`, `src/components/works/WorkGrid.vue`, `src/components/works/WorkDetail.vue`, `src/views/WorksView.vue`

- [ ] **Step 1: Create FilterTabs.vue**

```vue
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
```

- [ ] **Step 2: Create WorkGrid.vue**

```vue
<template>
  <div class="work-grid stagger-children">
    <PixelCard v-for="work in filteredWorks" :key="work.id" clickable @click="$emit('select', work)">
      <template #image>
        <img :src="work.image" :alt="work.title" loading="lazy" />
      </template>
      <h3 class="work-grid__title">{{ work.title }}</h3>
      <p class="work-grid__desc">{{ work.description }}</p>
      <div class="work-grid__meta">
        <span class="work-grid__tag">{{ getCategoryLabel(work.category) }}</span>
        <span class="work-grid__date">{{ work.date }}</span>
      </div>
    </PixelCard>
  </div>

  <p v-if="filteredWorks.length === 0" class="work-grid__empty">
    暂无该分类的作品，敬请期待！
  </p>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import PixelCard from '@/components/ui/PixelCard.vue'
import worksData from '@/data/works.json'

const props = defineProps<{ activeCategory: string }>()
defineEmits<{ select: [work: any] }>()

const filteredWorks = computed(() => {
  if (props.activeCategory === 'all') return worksData.works
  return worksData.works.filter(w => w.category === props.activeCategory)
})

function getCategoryLabel(key: string): string {
  return worksData.categories.find(c => c.key === key)?.label || key
}
</script>

<style scoped>
.work-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: var(--space-lg);
}

.work-grid__title {
  font-family: var(--font-display);
  font-size: var(--text-lg);
  color: var(--color-text);
  margin-bottom: var(--space-sm);
}

.work-grid__desc {
  font-size: var(--text-sm);
  color: var(--color-text-muted);
  margin-bottom: var(--space-md);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.work-grid__meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.work-grid__tag {
  font-family: var(--font-pixel);
  font-size: 0.5rem;
  color: var(--color-accent);
  padding: var(--space-xs) var(--space-sm);
  border: var(--border-width-sm) solid var(--color-accent-dim);
}

.work-grid__date {
  font-size: var(--text-xs);
  color: var(--color-text-muted);
}

.work-grid__empty {
  text-align: center;
  font-family: var(--font-display);
  font-size: var(--text-lg);
  color: var(--color-text-muted);
  padding: var(--space-3xl);
}
</style>
```

- [ ] **Step 3: Create WorkDetail.vue**

```vue
<template>
  <PixelModal :model-value="!!work" @update:model-value="$emit('close')">
    <div v-if="work" class="work-detail">
      <img :src="work.image" :alt="work.title" class="work-detail__image" />
      <h2 class="work-detail__title">{{ work.title }}</h2>
      <p class="work-detail__desc">{{ work.description }}</p>
      <div class="work-detail__meta">
        <span class="work-detail__meta-item">👤 {{ work.authors?.join(', ') }}</span>
        <span class="work-detail__meta-item">📅 {{ work.date }}</span>
        <span class="work-detail__tag">{{ getCategoryLabel(work.category) }}</span>
      </div>
    </div>
  </PixelModal>
</template>

<script setup lang="ts">
import PixelModal from '@/components/ui/PixelModal.vue'
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
```

- [ ] **Step 4: Create WorksView.vue**

```vue
<template>
  <PixelBackground>
    <AppHeader />
    <main class="works-page">
      <section class="page-hero">
        <h1 class="page-hero__title">作品展示</h1>
        <p class="page-hero__subtitle">燕理校园复刻工程及社团创作作品</p>
        <BlockDivider variant="grass" />
      </section>

      <div class="works-page__inner">
        <FilterTabs v-model="activeCategory" :categories="worksData.categories" />
        <WorkGrid :active-category="activeCategory" @select="selectedWork = $event" />
      </div>

      <WorkDetail :work="selectedWork" @close="selectedWork = null" />
    </main>
    <AppFooter />
  </PixelBackground>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import PixelBackground from '@/components/layout/PixelBackground.vue'
import AppHeader from '@/components/layout/AppHeader.vue'
import AppFooter from '@/components/layout/AppFooter.vue'
import BlockDivider from '@/components/ui/BlockDivider.vue'
import FilterTabs from '@/components/works/FilterTabs.vue'
import WorkGrid from '@/components/works/WorkGrid.vue'
import WorkDetail from '@/components/works/WorkDetail.vue'
import worksData from '@/data/works.json'

const activeCategory = ref('all')
const selectedWork = ref<any>(null)
</script>

<style scoped>
.works-page {
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

.works-page__inner {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: var(--space-2xl) var(--space-lg) var(--space-section);
}
</style>
```

- [ ] **Step 5: Commit**

```bash
git add src/components/works/ src/views/WorksView.vue && git commit -m "feat: add WorksView with filtering and detail modal"
```

---

### Task 13: NewsView

**Files:**
- Create: `src/components/news/NewsTimeline.vue`, `src/views/NewsView.vue`

- [ ] **Step 1: Create NewsTimeline.vue**

```vue
<template>
  <div class="timeline">
    <div v-for="(item, i) in newsItems" :key="item.id" class="timeline__item" :style="{ animationDelay: `${i * 0.15}s` }">
      <div class="timeline__marker">
        <span class="timeline__dot"></span>
        <span v-if="i < newsItems.length - 1" class="timeline__line"></span>
      </div>
      <div class="timeline__card block-inset">
        <div class="timeline__header">
          <span class="timeline__type" :class="`timeline__type--${item.type}`">{{ typeLabel(item.type) }}</span>
          <span class="timeline__date">{{ item.date }}</span>
        </div>
        <h3 class="timeline__title">{{ item.title }}</h3>
        <p class="timeline__summary">{{ item.summary }}</p>
        <p v-if="item.content" class="timeline__content">{{ item.content }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import newsData from '@/data/news.json'

const newsItems = newsData.news

function typeLabel(type: string): string {
  const labels: Record<string, string> = {
    announcement: '📢 公告',
    project: '🏗️ 项目',
    achievement: '🏆 成果',
    event: '🎉 活动',
  }
  return labels[type] || type
}
</script>

<style scoped>
.timeline {
  position: relative;
}

.timeline__item {
  display: flex;
  gap: var(--space-lg);
  animation: fadeInUp 0.5s var(--ease-pixel) both;
}

.timeline__marker {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;
}

.timeline__dot {
  width: 16px;
  height: 16px;
  background: var(--color-accent);
  border: var(--border-width-sm) solid var(--color-accent-dim);
  flex-shrink: 0;
}

.timeline__line {
  width: 2px;
  flex: 1;
  background: var(--color-surface-light);
  margin: var(--space-xs) 0;
}

.timeline__card {
  flex: 1;
  margin-bottom: var(--space-xl);
  padding: var(--space-lg);
  background: var(--color-surface);
}

.timeline__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-sm);
}

.timeline__type {
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  padding: var(--space-xs) var(--space-sm);
  border: var(--border-width-sm) solid;
}

.timeline__type--announcement { color: var(--color-gold); border-color: var(--color-gold-dim); }
.timeline__type--project { color: var(--color-accent); border-color: var(--color-accent-dim); }
.timeline__type--achievement { color: var(--color-gold); border-color: var(--color-gold-dim); }
.timeline__type--event { color: var(--color-water); border-color: var(--color-water); }

.timeline__date {
  font-size: var(--text-xs);
  color: var(--color-text-muted);
}

.timeline__title {
  font-family: var(--font-display);
  font-size: var(--text-lg);
  color: var(--color-text);
  margin-bottom: var(--space-sm);
}

.timeline__summary {
  font-size: var(--text-sm);
  color: var(--color-text-muted);
  line-height: 1.6;
}

.timeline__content {
  font-size: var(--text-sm);
  color: var(--color-text);
  margin-top: var(--space-md);
  padding-top: var(--space-md);
  border-top: var(--border-width-sm) dashed var(--color-surface-light);
  line-height: 1.6;
}
</style>
```

- [ ] **Step 2: Create NewsView.vue**

```vue
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
```

- [ ] **Step 3: Commit**

```bash
git add src/components/news/ src/views/NewsView.vue && git commit -m "feat: add NewsView with timeline layout"
```

---

### Task 14: MembersView

**Files:**
- Create: `src/components/members/MemberCard.vue`, `src/views/MembersView.vue`

- [ ] **Step 1: Create MemberCard.vue**

```vue
<template>
  <div class="member-card block-inset hover-lift">
    <div class="member-card__avatar">
      <span class="member-card__avatar-placeholder">{{ initial }}</span>
    </div>
    <h3 class="member-card__name">{{ member.name }}</h3>
    <span class="member-card__role">{{ member.role }}</span>
    <p class="member-card__desc">{{ member.description }}</p>
    <span class="member-card__grade">{{ member.grade }}</span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  member: {
    id: number
    name: string
    role: string
    avatar: string
    description: string
    grade: string
  }
}>()

const initial = computed(() => props.member.name.charAt(0))
</script>

<style scoped>
.member-card {
  padding: var(--space-xl);
  text-align: center;
  background: var(--color-surface);
}

.member-card__avatar {
  width: 80px;
  height: 80px;
  margin: 0 auto var(--space-md);
  background: var(--color-surface-light);
  border: var(--border-width) solid var(--color-brown);
  display: flex;
  align-items: center;
  justify-content: center;
}

.member-card__avatar-placeholder {
  font-family: var(--font-pixel);
  font-size: var(--text-2xl);
  color: var(--color-accent);
}

.member-card__name {
  font-family: var(--font-display);
  font-size: var(--text-lg);
  color: var(--color-text);
  margin-bottom: var(--space-xs);
}

.member-card__role {
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  color: var(--color-accent);
  padding: var(--space-xs) var(--space-md);
  border: var(--border-width-sm) solid var(--color-accent-dim);
  display: inline-block;
  margin-bottom: var(--space-md);
}

.member-card__desc {
  font-size: var(--text-sm);
  color: var(--color-text-muted);
  margin-bottom: var(--space-md);
}

.member-card__grade {
  font-size: var(--text-xs);
  color: var(--color-brown);
}
</style>
```

- [ ] **Step 2: Create MembersView.vue**

```vue
<template>
  <PixelBackground>
    <AppHeader />
    <main class="members-page">
      <section class="page-hero">
        <h1 class="page-hero__title">成员风采</h1>
        <p class="page-hero__subtitle">YITMC 管理团队</p>
        <BlockDivider variant="grass" />
      </section>

      <div class="members-page__inner">
        <div class="members-page__grid stagger-children">
          <MemberCard v-for="member in membersData.leadership" :key="member.id" :member="member" />
        </div>

        <div class="members-page__join">
          <p>想要成为YITMC的一员吗？</p>
          <PixelButton to="/join" variant="gold">加入我们</PixelButton>
        </div>
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
import PixelButton from '@/components/ui/PixelButton.vue'
import MemberCard from '@/components/members/MemberCard.vue'
import membersData from '@/data/members.json'
</script>

<style scoped>
.members-page {
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

.members-page__inner {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: var(--space-2xl) var(--space-lg) var(--space-section);
}

.members-page__grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: var(--space-lg);
}

.members-page__join {
  text-align: center;
  margin-top: var(--space-3xl);
  padding: var(--space-2xl);
  background: var(--color-surface);
  border: var(--border-width) solid var(--color-brown);
}

.members-page__join p {
  font-family: var(--font-display);
  font-size: var(--text-lg);
  color: var(--color-text);
  margin-bottom: var(--space-lg);
}
</style>
```

- [ ] **Step 3: Commit**

```bash
git add src/components/members/ src/views/MembersView.vue && git commit -m "feat: add MembersView with team cards"
```

---

### Task 15: AboutView

**Files:**
- Create: `src/views/AboutView.vue`

- [ ] **Step 1: Create AboutView.vue**

```vue
<template>
  <PixelBackground>
    <AppHeader />
    <main class="about-page">
      <section class="page-hero">
        <h1 class="page-hero__title">关于我们</h1>
        <p class="page-hero__subtitle">燕京理工学院 MC 玩家创作协会</p>
        <BlockDivider variant="grass" />
      </section>

      <div class="about-page__inner">
        <div class="about-page__section block-inset">
          <h2>📖 社团简介</h2>
          <p>YITMC（燕京理工学院MC玩家创作协会）成立于2023年，是燕京理工学院<b>官方注册</b>的学生社团组织。</p>
          <p>我们以 Minecraft 为创作平台，聚集热爱建筑、创意与协作的同学们。社团核心项目 — <b>燕京理工学院校园复刻工程</b>，旨在通过 Minecraft 1:1 还原校园建筑与景观，已完成教学楼、图书馆、行政楼、宿舍区等多个区域的精确复刻。</p>
          <p>我们的作品定期在抖音与B站平台更新发布，已积累一定数量的关注者。社团定期举办建筑创作活动、技术分享会和校园文化展览。</p>
        </div>

        <div class="about-page__section block-inset">
          <h2>🎯 社团宗旨</h2>
          <p class="about-page__motto">"用方块还原校园，用创意连接世界"</p>
          <ul class="about-page__list">
            <li>🎮 为MC爱好者提供交流与创作平台</li>
            <li>🏗️ 通过建筑创作培养空间思维与团队协作能力</li>
            <li>📸 展示校园之美，提升学校在数字创意领域的影响力</li>
            <li>🤝 促进跨年级、跨专业的同学交流与合作</li>
          </ul>
        </div>

        <div class="about-page__section block-inset">
          <h2>🏛️ 官方信息</h2>
          <div class="about-page__info-grid">
            <div class="about-page__info-item">
              <span class="about-page__info-label">所属院校</span>
              <span class="about-page__info-value">{{ config.schoolName }}</span>
            </div>
            <div class="about-page__info-item">
              <span class="about-page__info-label">注册状态</span>
              <span class="about-page__info-value about-page__info-value--gold">{{ config.registrationInfo }}</span>
            </div>
            <div class="about-page__info-item">
              <span class="about-page__info-label">成立时间</span>
              <span class="about-page__info-value">2023年</span>
            </div>
            <div class="about-page__info-item">
              <span class="about-page__info-label">指导单位</span>
              <span class="about-page__info-value">燕京理工学院团委</span>
            </div>
          </div>
        </div>

        <div class="about-page__section block-inset">
          <h2>📬 联系方式</h2>
          <p>QQ群：<b>{{ config.qqGroup }}</b></p>
          <p>邮箱：{{ config.email }}</p>
          <p>抖音：{{ config.douyinHandle }} ｜ B站：{{ config.bilibiliHandle }}</p>
        </div>
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
import siteConfig from '@/data/site-config.json'

const config = siteConfig
</script>

<style scoped>
.about-page {
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

.about-page__inner {
  max-width: 800px;
  margin: 0 auto;
  padding: var(--space-2xl) var(--space-lg) var(--space-section);
  display: flex;
  flex-direction: column;
  gap: var(--space-xl);
}

.about-page__section {
  padding: var(--space-xl);
  background: var(--color-surface);
}

.about-page__section h2 {
  font-family: var(--font-pixel);
  font-size: var(--text-sm);
  color: var(--color-accent);
  margin-bottom: var(--space-lg);
  text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5);
}

.about-page__section p {
  font-size: var(--text-base);
  color: var(--color-text-muted);
  line-height: 1.8;
  margin-bottom: var(--space-sm);
}

.about-page__section p b {
  color: var(--color-accent);
}

.about-page__motto {
  font-family: var(--font-display);
  font-size: var(--text-xl) !important;
  color: var(--color-gold) !important;
  text-align: center;
  padding: var(--space-md);
  border: var(--border-width-sm) dashed var(--color-gold-dim);
}

.about-page__list {
  list-style: none;
  margin-top: var(--space-md);
}

.about-page__list li {
  font-size: var(--text-base);
  color: var(--color-text-muted);
  padding: var(--space-sm) 0;
  border-bottom: var(--border-width-sm) solid var(--color-surface-light);
}

.about-page__info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--space-md);
}

.about-page__info-item {
  padding: var(--space-md);
  border: var(--border-width-sm) solid var(--color-surface-light);
}

.about-page__info-label {
  display: block;
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  color: var(--color-text-muted);
  margin-bottom: var(--space-xs);
}

.about-page__info-value {
  font-size: var(--text-base);
  color: var(--color-text);
}

.about-page__info-value--gold {
  color: var(--color-gold);
}

@media (max-width: 768px) {
  .about-page__info-grid {
    grid-template-columns: 1fr;
  }
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add src/views/AboutView.vue && git commit -m "feat: add AboutView with club info and official registration"
```

---

### Task 16: JoinView

**Files:**
- Create: `src/components/join/CopyText.vue`, `src/components/join/QRCodeCard.vue`, `src/views/JoinView.vue`

- [ ] **Step 1: Create CopyText.vue**

```vue
<template>
  <div class="copy-text block-inset">
    <span class="copy-text__label">{{ label }}</span>
    <div class="copy-text__row">
      <code class="copy-text__value">{{ text }}</code>
      <button class="copy-text__btn" @click="copy" :class="{ 'copy-text__btn--done': copied }">
        {{ copied ? '✓ 已复制' : '📋 复制' }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{ label: string; text: string }>()
const copied = ref(false)

async function copy() {
  await navigator.clipboard.writeText(props.text)
  copied.value = true
  setTimeout(() => { copied.value = false }, 2000)
}
</script>

<style scoped>
.copy-text {
  padding: var(--space-lg);
  background: var(--color-surface);
}

.copy-text__label {
  display: block;
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  color: var(--color-accent);
  margin-bottom: var(--space-sm);
}

.copy-text__row {
  display: flex;
  gap: var(--space-sm);
  align-items: center;
}

.copy-text__value {
  flex: 1;
  font-family: var(--font-body);
  font-size: var(--text-lg);
  color: var(--color-text);
  background: var(--color-bg);
  padding: var(--space-sm) var(--space-md);
  border: var(--border-width-sm) solid var(--color-surface-light);
}

.copy-text__btn {
  font-family: var(--font-pixel);
  font-size: var(--text-xs);
  padding: var(--space-sm) var(--space-md);
  background: var(--color-surface-light);
  color: var(--color-text);
  border: var(--border-width-sm) solid var(--color-brown);
  cursor: pointer;
  white-space: nowrap;
  transition: all var(--duration-fast) var(--ease-pixel);
}

.copy-text__btn:hover {
  border-color: var(--color-accent);
  color: var(--color-accent);
}

.copy-text__btn--done {
  background: var(--color-accent);
  color: #fff !important;
  border-color: var(--color-accent-dim);
}
</style>
```

- [ ] **Step 2: Create QRCodeCard.vue**

```vue
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
```

- [ ] **Step 3: Create JoinView.vue**

```vue
<template>
  <PixelBackground>
    <AppHeader />
    <main class="join-page">
      <section class="page-hero">
        <h1 class="page-hero__title">加入我们</h1>
        <p class="page-hero__subtitle">一起用方块创造无限可能！</p>
        <BlockDivider variant="grass" />
      </section>

      <div class="join-page__inner">
        <div class="join-page__grid">
          <div class="join-page__section">
            <h2>💬 加入QQ群</h2>
            <CopyText label="QQ群号" :text="config.qqGroup" />
            <a :href="config.qqGroupJoinUrl" target="_blank" rel="noopener" class="join-page__direct-link">
              <PixelButton variant="primary" block>一键加群</PixelButton>
            </a>
          </div>

          <div class="join-page__section">
            <QRCodeCard title="微信群" :qr-url="config.wechatGroupQrUrl" hint="扫描二维码加入微信群" />
          </div>
        </div>

        <div class="join-page__section">
          <h2>📱 关注我们</h2>
          <div class="join-page__social-grid">
            <SocialBlock icon="🎵" platform="抖音" :handle="config.douyinHandle" :href="config.douyinUrl" />
            <SocialBlock icon="📺" platform="B站" :handle="config.bilibiliHandle" :href="config.bilibiliUrl" />
          </div>
        </div>

        <div class="join-page__section block-inset">
          <h2>❓ 常见问题</h2>
          <div class="join-page__faq">
            <details class="join-page__faq-item">
              <summary>加入社团有什么要求？</summary>
              <p>只要是燕京理工学院在校学生，对Minecraft或建筑创作有兴趣即可加入，无任何门槛！</p>
            </details>
            <details class="join-page__faq-item">
              <summary>我没有Minecraft正版账号可以加入吗？</summary>
              <p>可以！社团服务器支持多种登录方式，具体请联系管理员获取帮助。</p>
            </details>
            <details class="join-page__faq-item">
              <summary>社团活动一般在什么时间？</summary>
              <p>线上活动灵活安排，线下活动通常安排在周末或课余时间，不影响正常学习。</p>
            </details>
            <details class="join-page__faq-item">
              <summary>不会建筑怎么办？</summary>
              <p>没关系！社团有技术培训和指导，只要你有兴趣和热情，我们可以一起学习进步。</p>
            </details>
          </div>
        </div>
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
import PixelButton from '@/components/ui/PixelButton.vue'
import SocialBlock from '@/components/ui/SocialBlock.vue'
import CopyText from '@/components/join/CopyText.vue'
import QRCodeCard from '@/components/join/QRCodeCard.vue'
import siteConfig from '@/data/site-config.json'

const config = siteConfig
</script>

<style scoped>
.join-page {
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

.join-page__inner {
  max-width: 800px;
  margin: 0 auto;
  padding: var(--space-2xl) var(--space-lg) var(--space-section);
  display: flex;
  flex-direction: column;
  gap: var(--space-xl);
}

.join-page__section {
  padding: var(--space-xl);
  background: var(--color-surface);
  border: var(--border-width) solid var(--color-surface-light);
}

.join-page__section h2 {
  font-family: var(--font-pixel);
  font-size: var(--text-sm);
  color: var(--color-accent);
  margin-bottom: var(--space-lg);
  text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5);
}

.join-page__grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-lg);
}

.join-page__direct-link {
  display: block;
  margin-top: var(--space-md);
}

.join-page__social-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--space-md);
}

.join-page__faq-item {
  border-top: var(--border-width-sm) solid var(--color-surface-light);
}

.join-page__faq-item:last-child {
  border-bottom: var(--border-width-sm) solid var(--color-surface-light);
}

.join-page__faq-item summary {
  font-family: var(--font-display);
  font-size: var(--text-base);
  color: var(--color-text);
  padding: var(--space-md);
  cursor: pointer;
  list-style: none;
}

.join-page__faq-item summary::before {
  content: '▶ ';
  font-size: var(--text-xs);
  color: var(--color-accent);
}

.join-page__faq-item[open] summary::before {
  content: '▼ ';
}

.join-page__faq-item p {
  font-size: var(--text-sm);
  color: var(--color-text-muted);
  padding: 0 var(--space-md) var(--space-md);
  line-height: 1.6;
}

@media (max-width: 768px) {
  .join-page__grid {
    grid-template-columns: 1fr;
  }
}
</style>
```

- [ ] **Step 4: Commit**

```bash
git add src/components/join/ src/views/JoinView.vue && git commit -m "feat: add JoinView with QQ/WeChat/FAQ"
```

---

### Task 17: GSAP Scroll Animations

**Files:**
- Create: `src/composables/useScrollReveal.ts`
- Modify: `src/views/HomeView.vue` (add scroll reveal)

- [ ] **Step 1: Create src/composables/useScrollReveal.ts**

```typescript
// Lightweight scroll reveal using Intersection Observer.
// Avoids importing full GSAP to keep bundle small.
// For GSAP-heavy work, dynamically import gsap + ScrollTrigger.

export function useScrollReveal() {
  let observer: IntersectionObserver | null = null

  function init(selector: string = '[data-reveal]') {
    if (typeof window === 'undefined') return

    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-revealed')
            observer?.unobserve(entry.target)
          }
        })
      },
      { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    )

    document.querySelectorAll(selector).forEach((el) => {
      observer?.observe(el)
    })
  }

  function destroy() {
    observer?.disconnect()
    observer = null
  }

  return { init, destroy }
}
```

- [ ] **Step 2: Add reveal styles to src/styles/animations.css**

Append to end of `src/styles/animations.css`:

```css
/* Scroll reveal via Intersection Observer */
[data-reveal] {
  opacity: 0;
  transform: translateY(24px);
  transition: opacity 0.6s steps(6), transform 0.6s steps(6);
}

[data-reveal].is-revealed {
  opacity: 1;
  transform: translateY(0);
}
```

- [ ] **Step 3: Commit**

```bash
git add src/composables/ && git commit -m "feat: add scroll reveal composable and styles"
```

---

### Task 18: Final Integration — Verify Build & Polish

**Files:**
- Modify: `tailwind.config.ts` (if needed), `package.json` (verify scripts)

- [ ] **Step 1: Verify Vite dev build**

Run: `cd /mnt/a/liu23/Documents/work/YITMC && npx vite build 2>&1`
Expected: build succeeds without errors

- [ ] **Step 2: Fix any missing imports or type errors**

Review build output and fix any issues.

- [ ] **Step 3: Verify all routes work**

Run: `cd /mnt/a/liu23/Documents/work/YITMC && npx vite preview --host 0.0.0.0 --port 4173 &`
Expected: All 6 routes render without blank pages

- [ ] **Step 4: Commit**

```bash
git add -A && git commit -m "chore: final integration, build verification, and polish"
```

---

## Plan Metadata

- **Total Tasks:** 18
- **Estimated Files:** ~35 files created
- **Pages:** 6 complete views
- **Components:** 15+ reusable components
