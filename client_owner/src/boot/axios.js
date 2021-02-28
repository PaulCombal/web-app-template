import axios from 'axios'
import {Notify} from "quasar";

axios.defaults.baseURL = process.env.API_URL || 'set a default here';
axios.defaults.withCredentials = true;

export default ({store, Vue, router}) => {
  axios.interceptors.response.use(res => res, function (err) {
    console.log('[Axios] Intercepted response')

    if (err.response.status === 401 && err.config && !err.config.__isRetryRequest) {
      console.log('Received a 401, not a retryRequest, asking store to fresh login')
      err.config.__isRetryRequest = true

      return store.dispatch('auth/fresh_login')
        .catch(e => {
          console.error('[Axios] Store could not fresh login: ', e)
          console.log('[Axios] Redirecting to login page for user to authenticate')

          if (router.currentRoute.path !== '/login') {
            router.push('/login')
            Notify.create({
              message: 'You need to login first',
              type: 'info',
              position: 'top'
            })
          }

          return Promise.reject(err)
        })
        .then(_ => {
          console.log('[Axios] Successfully acquired a fresh login from the store! Retrying request')
          return axios.request(err.config)
        })
    } else if (err.config && err.config.__isRetryRequest) {
      console.error('Bad auth after retyrequest', err);
      Notify.create({
        message: 'An authentication error occurred. Please try again later.',
        type: 'negative',
        position: 'top'
      })
    }

    return Promise.reject(err)
  });

  Vue.prototype.$axios = axios
  store.$axios = axios
}
