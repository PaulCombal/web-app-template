export default async ({ router, store }) => {
  // "/" allows login
  router.beforeEach((to, from, next) => {
    const logged = store.getters['auth/loggedIn']
    if (!logged && to.path !== '/') {
      if (from.path === '/') {
        alert('Page protégée, connexion requise!')
      }
      next('/')
    } else {
      next()
    }
  })
}
