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
