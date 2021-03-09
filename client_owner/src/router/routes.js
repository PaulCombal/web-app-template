
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: 'users', component: () => import('pages/Users') },
    ]
  },

  {
    path: '/login',
    component: () => import('layouts/BlankLayout'),
    children: [
      { path: '', component: () => import('pages/Login') },
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
