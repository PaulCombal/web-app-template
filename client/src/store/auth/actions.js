import {Platform} from "quasar";
import 'app/src-capacitor/node_modules/@codetrix-studio/capacitor-google-auth';
import {Plugins} from '@capacitor/core'

const LOGIN_ROUTE_GOOGLE = 'login/google/check_token'
const LOGOUT_ROUTE_ALL = 'logout'

/**
 * When we handled the first logins steps manually elsewhere
 * Most likely on a new login
 *
 * data: obj .user .op
 */
export function front_login(state, data) {
  state.commit('setUser', data.user)
  localStorage.setItem('user', JSON.stringify(data.user))
  localStorage.setItem('last_op', data.op)
}

/**
 * When we already logged in, and need a refresh
 *
 * @param state
 * @returns {Promise<unknown>}
 */
export function fresh_login(state) {
  const last_op = localStorage.getItem('last_op')
  let route = null;
  let pchain = null;

  if (!last_op) return Promise.reject('No last OP')


  switch (last_op) {
    case 'google':
      route = LOGIN_ROUTE_GOOGLE;
      if (!Platform.is.mobile) { // web
        pchain = window.Capacitor.Plugins.GoogleAuth.gapiLoaded.then(() => {
          if (!window.gapi.auth2.getAuthInstance().isSignedIn.get()) {
            return Promise.reject('Cannot fresh_login: Google user is not signed in')
          }

          // @see Plugin.GoogleAuthWeb.refresh
          return window.gapi.auth2.getAuthInstance()
            .currentUser.get().reloadAuthResponse()
            .then(r => r.id_token)
        })
      } else {
        // Capacitor
        pchain = Plugins.GoogleAuth.signIn()
          .then(gu => {
            if (!gu.idToken) {
              console.error('Unknown capacitor googleUser format in fresh_login', gu);
              throw 'Bad token from capacitor in fresh_login'
            }
            return gu.idToken;
          })
      }
      break;

    default:
      return Promise.reject('unknown op: ' + last_op)
  }

  return pchain
    .then(id_token => {
      return state.dispatch('validate_idtoken', {
        route,
        op: last_op,
        body: {id_token}
      });
    })
}

/**
 * Give it data, and it will validate id token, by making a request to backend
 * it returns the dispatch to mutate the store on success
 *
 * @param state
 * @param data
 * @returns {Promise<any>}
 */
export function validate_idtoken(state, data) {
  return this.$axios
    .post(data.route, data.body)
    .then(response => {
      console.log('[Auth] id_token validated by backend server. Cookies must be included in the response.')
      return state.dispatch('front_login', {user: response.data, op: data.op})
    })
}

/**
 * When we reload the app, must check if token is still there and valid
 * if so, no request to do
 * if not, dispatch a fresh_login
 *
 * @param state
 * @returns {Promise<unknown>}
 */
export function resume_login(state) {
  return new Promise(function (resolve, reject) {
    console.log('Trying to resume login...')
    const j = localStorage.getItem('user');
    if (j) {
      console.log('Potential session to resume..')
      const user = JSON.parse(j);
      const exp = (user.exp - 60) * 1000; // If 60 seconds left on token, refresh it anyway
      const exp_date = new Date(exp)

      if (new Date() > exp_date) {
        // token must be refreshed
        console.log('Cannot resume: refreshing token first')
        state.dispatch('fresh_login')
      } else {
        // we can still use current token
        console.log('Session resumed!')
        state.commit('setUser', user)
      }

      resolve()
    } else {
      // Nothing on localStorage -> user must be explicitly disconnected
      console.log('No session to resume. Logging out.')
      return state.dispatch('logout')
    }
  })
}

export function logout(state) {
  localStorage.removeItem('user')
  localStorage.removeItem('last_op')
  state.commit('setUser', null)
  window.Capacitor.Plugins.GoogleAuth.gapiLoaded.then(()=>Plugins.GoogleAuth.signOut())
  return this.$axios
    .get(LOGOUT_ROUTE_ALL)
    .then(r => r.data)
    .then(d => {
      if (d.message !== 'ok') {
        throw 'logout message is not ok'
      }
    })
}
