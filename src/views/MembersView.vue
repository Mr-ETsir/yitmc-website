<template>
  <PixelBackground>
    <AppHeader />
    <main class="members-page">
      <section class="page-hero">
        <h1 class="page-hero__title" data-reveal>成员风采</h1>
        <p class="page-hero__subtitle" data-reveal data-reveal-delay="1">YITMC 管理团队</p>
        <BlockDivider variant="grass" />
      </section>

      <div class="members-page__inner">
        <div class="members-page__grid stagger-children" data-reveal>
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
import { membersData } from '@/data'
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
