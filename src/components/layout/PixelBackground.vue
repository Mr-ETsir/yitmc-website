<template>
  <div class="pixel-bg">
    <div class="pixel-bg__grid"></div>
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
  transform: translateZ(0); /* GPU layer — prevents scroll repaints */
  background-image:
    linear-gradient(rgba(92, 154, 59, 0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(92, 154, 59, 0.04) 1px, transparent 1px);
  background-size: 32px 32px;
}

.pixel-bg > :not(.pixel-bg__grid) {
  position: relative;
  z-index: 1;
}
</style>
