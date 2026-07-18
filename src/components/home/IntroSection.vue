<template>
  <section class="intro">
    <BlockDivider variant="grass" />
    <div class="intro__inner">
      <h2 class="intro__heading">关于 YITMC</h2>
      <div class="intro__grid">
        <div class="intro__text">
          <p>燕理MC创作协会，于<b>2024年</b>由燕京理工学院内热爱《我的世界》游戏的学生自发创建。</p>
          <p>协会为成员们提供了一个集<b>交流、学习、创新和协作</b>为一体的平台，旨在推动游戏技术与创意的发展。协会汇聚了对游戏开发充满热情的学生，他们不仅探索游戏世界，更将其视为学习和创新的工具。</p>
          <p>通过分享<b>建筑、电路设计和模组开发</b>等技能，成员们不断提升自己并将所学应用于实际创作中。社团核心项目——<b>燕京理工学院校园复刻工程</b>，在抖音与B站等平台持续更新。</p>
        </div>
        <div class="intro__badge">
          <img src="/img/官方社团证明.jpg" alt="官方社团注册证明" class="intro__proof-img" />
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
  justify-content: center;
  align-items: flex-start;
}

.intro__proof-img {
  max-width: 100%;
  max-height: 320px;
  object-fit: contain;
  border: var(--border-width) solid var(--color-brown);
  box-shadow: var(--shadow-pixel);
  background: var(--color-surface);
}

@media (max-width: 768px) {
  .intro__grid {
    grid-template-columns: 1fr;
  }
}
</style>
