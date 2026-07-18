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
  will-change: transform;
  transition: transform 0.15s var(--ease-spring),
              box-shadow 0.15s var(--ease-apple-out),
              background 0.15s var(--ease-apple-out),
              border-color 0.15s var(--ease-apple-out);
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
  transform: translate3d(-2px, -2px, 0);
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
  transform: translate3d(-2px, -2px, 0);
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
  transform: translate3d(-2px, -2px, 0);
  box-shadow: var(--shadow-pixel-lg);
}

.pixel-btn:active {
  transform: translate3d(0, 0, 0) !important;
  box-shadow: none !important;
}
</style>
