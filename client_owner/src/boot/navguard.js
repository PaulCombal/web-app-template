export default async ({ router, store }) => {
  // "/" allows login
  router.beforeEach((to, from, next) => {
    const logged = store.getters['auth/loggedIn']
    if (!logged && to.path !== '/') {
      if (from.path === '/') {
        alert('Please login to access this page.')
      }
      next('/')
    } else {
      next()
    }
  })
}
