# 燕京理工学院 MC 玩家创作协会 官方网站 — 设计规范

## 项目概述

为燕京理工学院 MC 玩家创作协会（YITMC）打造官方综合门户网站。网站采用 Minecraft 像素体素美学风格，兼具招新展示和作品展廊功能。

## 技术栈

- **框架**: Vue 3 + Vite
- **路由**: Vue Router 4
- **样式**: Tailwind CSS + 自定义 CSS 变量（像素主题）
- **动画**: CSS Animations + GSAP (GreenSock)
- **部署**: 静态托管（Vite build）

## 设计系统

### 配色方案

| 用途 | 色值 | 说明 |
|------|------|------|
| 背景主色 | `#1a1a1a` | 暗色背景，模拟地下矿洞氛围 |
| 表面卡片 | `#2d2d2d` | 类似石头方块质感 |
| 强调色 | `#5c9a3b` | MC 草地绿，按钮、链接、高亮 |
| 辅助强调 | `#c68a4b` | 泥土棕，边框、标签、次要元素 |
| 文字主色 | `#e8e8e8` | 高可读性浅灰白 |
| 金色点缀 | `#f5c842` | 类似金锭/附魔效果，重点标记 |

### 字体方案

- 英文像素标题：`"Press Start 2P"` (Google Fonts)
- 中文标题：`"ZCOOL KuaiLe"` (Google Fonts / 思源字体)
- 正文：系统等宽 + 像素字体降级方案

### UI 元素风格

- 粗方块边框（4px solid），模拟 MC 方块边缘
- hover 时边框色变为草绿 + 轻微像素化位移
- 背景使用 CSS 方块纹理网格
- 自定义滚动条（MC 风格）

### 动画风格

- 页面切换：方块碎裂/组合过渡
- 卡片 hover：像素化浮起效果（阶梯式 transform + box-shadow）
- 滚动出现：逐块揭示（block reveal）
- 加载动画：像素方块堆叠

---

## 页面结构

### 1. 首页 `/`

| 区块 | 内容 |
|------|------|
| Hero | 社团 Logo + "YITMC" 像素动画标题，背景铺设学校复刻截图 mosaic，副标题"燕京理工学院 MC 玩家创作协会"，CTA 按钮（查看作品 / 加入我们）|
| 社团简介 | 简短介绍 + 官方注册社团认证标识 |
| 作品精选 | 3-4 张精选复刻截图卡片，链接到作品展示页 |
| 数据亮点 | 像素风格数字展示（社团人数、项目数、活动次数等）|
| 社交媒体入口 | 抖音 / B站 图标方块卡片 |

### 2. 作品展示 `/works`

- 筛选标签（建筑 / 景观 / 校园复刻 / 活动作品）
- 图片网格画廊，方块边框卡片
- 点击放大查看 + 描述弹窗

### 3. 社团动态 `/news`

- 活动新闻 / 公告列表
- 像素风格时间线布局

### 4. 成员风采 `/members`

- 管理团队卡片
- 成员作品精选展示

### 5. 关于我们 `/about`

- 社团历史和宗旨
- 官方注册信息
- 指导老师介绍

### 6. 加入我们 `/join`

- QQ群号 + 一键复制
- 微信群二维码展示
- 抖音号 + B站号 + 外部链接
- 招新要求 / FAQ

---

## 组件架构

```
src/
├── components/
│   ├── layout/
│   │   ├── AppHeader.vue       # 像素导航栏
│   │   ├── AppFooter.vue       # 页脚
│   │   └── PixelBackground.vue # 方块纹理背景
│   ├── ui/
│   │   ├── PixelButton.vue     # 像素方块按钮
│   │   ├── PixelCard.vue       # 像素方块卡片
│   │   ├── PixelModal.vue      # 像素弹窗
│   │   ├── BlockDivider.vue    # 方块分隔线
│   │   ├── AnimatedCounter.vue # 像素数字滚动
│   │   └── SocialBlock.vue     # 社交媒体方块入口
│   ├── home/
│   │   ├── HeroSection.vue     # Hero 区块
│   │   ├── IntroSection.vue    # 社团简介
│   │   ├── FeaturedWorks.vue   # 精选作品
│   │   ├── StatsSection.vue    # 数据亮点
│   │   └── SocialSection.vue   # 社交媒体
│   ├── works/
│   │   ├── FilterTabs.vue      # 分类筛选标签
│   │   ├── WorkGrid.vue        # 作品网格
│   │   └── WorkDetail.vue      # 作品详情弹窗
│   ├── news/
│   │   └── NewsTimeline.vue    # 动态时间线
│   ├── members/
│   │   └── MemberCard.vue      # 成员卡片
│   └── join/
│       ├── CopyText.vue        # 一键复制组件
│       └── QRCodeCard.vue      # 二维码展示卡片
├── views/
│   ├── HomeView.vue
│   ├── WorksView.vue
│   ├── NewsView.vue
│   ├── MembersView.vue
│   ├── AboutView.vue
│   └── JoinView.vue
├── router/
│   └── index.ts
├── styles/
│   ├── tokens.css              # 设计 token 变量
│   ├── fonts.css               # 字体引入
│   ├── blocks.css              # 方块纹理/边框工具类
│   └── animations.css          # 像素动画定义
├── assets/
│   └── (logo + 背景截图)
└── App.vue
```

## 路由设计

| 路径 | 组件 | 标题 |
|------|------|------|
| `/` | HomeView | YITMC - 首页 |
| `/works` | WorksView | 作品展示 |
| `/news` | NewsView | 社团动态 |
| `/members` | MembersView | 成员风采 |
| `/about` | AboutView | 关于我们 |
| `/join` | JoinView | 加入我们 |

## 数据管理

- 静态数据使用 JSON 文件管理（作品列表、成员信息、动态列表、社团信息等）
- 后续可轻松替换为 CMS 或 API 数据源
- 联系信息（QQ群号、微信号、抖音号、B站号）集中配置

```
src/data/
├── works.json       # 作品列表
├── news.json        # 动态/公告
├── members.json     # 成员信息
├── site-config.json # 网站全局配置（联系信息、社媒链接等）
└── stats.json       # 首页数据亮点
```

## 性能目标

- Lighthouse Score ≥ 90
- LCP < 2s
- 图片使用 WebP 格式 + 懒加载
- 字体使用 `font-display: swap`
- GSAP 按需动态导入
- Vite 代码分割（按路由）

## 测试策略

1. 组件单元测试 (Vitest) — 覆盖核心 UI 组件
2. 页面 E2E 测试 (Playwright) — 关键用户流程
3. 视觉回归测试 — 像素风格关键页面截图对比
4. 响应式测试 — 320 / 768 / 1024 / 1440 断点
