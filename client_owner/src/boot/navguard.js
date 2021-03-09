export default async ({ router, store }) => {
  router.beforeEach((to, from, next) => {
    const logged = store.getters['auth/loggedIn']
    if (!logged && to.path !== '/login') {
      if (from.path === '/login') {
        alert('Page protégée, connexion requise!')
      }
      next('/login')
    } else {
      next()
    }
  })
}
