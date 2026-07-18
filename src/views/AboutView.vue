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
          <h2><AppIcon name="book-open" :size="18" /> 社团简介</h2>
          <p>燕理MC创作协会（YITMC），于<b>2024年</b>由燕京理工学院内热爱《我的世界》游戏的学生自发创建。是燕京理工学院<b>官方注册</b>的学生社团组织。</p>
          <p>协会为成员们提供了一个集<b>交流、学习、创新和协作</b>为一体的平台，旨在推动游戏技术与创意的发展。协会汇聚了对游戏开发充满热情的学生，他们不仅探索游戏世界，更将其视为学习和创新的工具。</p>
          <p>通过分享<b>建筑、电路设计和模组开发</b>等技能，成员们不断提升自己并将所学应用于实际创作中。社团核心项目——<b>燕京理工学院校园复刻工程</b>，一期已建成教学楼区及宿舍区，二期覆盖图书馆、西侧操场及体育馆等区域，作品定期在抖音与B站平台更新发布。</p>
        </div>

        <div class="about-page__section block-inset">
          <h2><AppIcon name="target" :size="18" /> 社团宗旨</h2>
          <p class="about-page__motto">"用方块还原校园，用创意连接世界"</p>
          <ul class="about-page__list">
            <li><AppIcon name="gamepad" :size="16" /> 为MC爱好者提供交流与创作平台</li>
            <li><AppIcon name="construction" :size="16" /> 通过建筑创作培养空间思维与团队协作能力</li>
            <li><AppIcon name="camera" :size="16" /> 展示校园之美，提升学校在数字创意领域的影响力</li>
            <li><AppIcon name="users" :size="16" /> 促进跨年级、跨专业的同学交流与合作</li>
          </ul>
        </div>

        <div class="about-page__section block-inset">
          <h2><AppIcon name="building" :size="18" /> 官方信息</h2>
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
              <span class="about-page__info-value">2024年</span>
            </div>
            <div class="about-page__info-item">
              <span class="about-page__info-label">指导单位</span>
              <span class="about-page__info-value">燕京理工学院信息科学与技术学院社团管理部</span>
            </div>
          </div>
        </div>

        <div class="about-page__section block-inset">
          <h2><AppIcon name="users" :size="18" /> 组织成员</h2>
          <div class="about-page__info-grid">
            <div class="about-page__info-item">
              <span class="about-page__info-label">MUA 高校联盟</span>
              <span class="about-page__info-value about-page__info-value--gold">正式成员</span>
            </div>
            <div class="about-page__info-item">
              <span class="about-page__info-label">VCAC 体素专委会</span>
              <span class="about-page__info-value about-page__info-value--gold">正式成员</span>
            </div>
          </div>
        </div>

        <div class="about-page__section block-inset">
          <h2><AppIcon name="mail" :size="18" /> 联系方式</h2>
          <p>QQ群：<b>{{ config.qqGroup }}</b></p>
          <p>社长微信：<b>{{ config.presidentWechat }}</b></p>
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
import AppIcon from '@/components/icons/AppIcon.vue'
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
