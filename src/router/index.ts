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
    {
      path: '/admin',
      name: 'admin',
      component: () => import('@/views/AdminView.vue'),
      meta: { title: '网站内容管理 - YITMC' },
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
