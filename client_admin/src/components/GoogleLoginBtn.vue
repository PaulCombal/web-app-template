<template>
  <q-btn ref="signinBtn">Login with Google</q-btn>
</template>

<script>

/**
 * STOP!
 *
 * I know what you're thinking:
 * Why is this dumbass using gapi manually when he uses @codetrix's plugin for handling that?
 * That's because their web implementation fucking sucks and is not documented, so I'm doing it myself,
 * making the process suck even more, but at least I get the intended result.
 */
export default {
  name: 'GoogleLoginBtn',
  props: {
    autoValidate: {
      type: Boolean,
      default: true
    }
  },
  mounted() {
    // This is really sad yet effective
    window.gapiLoaded.then(() => {
      window.gapi.auth2.getAuthInstance().attachClickHandler(this.$refs.signinBtn.$el, {}, this.googleUserCb, error => {
        console.error('GoogleButton could not load auth2', error)
      })
    })
  },
  methods: {
    googleUserCb(googleUser) {
      this.$emit('success', googleUser)
      if (this.autoValidate) {
        return this.validate(googleUser)
      }
    },
    validate(token) {
      let id_token = null
      if (token.getAuthResponse && token.getAuthResponse().id_token) {
        id_token = token.getAuthResponse().id_token;
      } else if (!(id_token = token.idToken)) { // Capacitor
        return console.error('No way to extract id token from googleUser', token)
      }

      this.$axios.post('login/google/check_token', {id_token, role: 'ROLE_ADMIN'})
        .then(r => r.data)
        .then(user => this.$store.dispatch('auth/front_login', {user, op: 'google'}))
        .catch(e => {
          this.$store.dispatch("auth/logout")
          console.error(e)
          this.$q.notify({
            type: 'warning',
            message: 'Something went wrong while logging you in.. Please try again later or try clearing your browser',
            position: 'top'
          })
        })
    }
  }
}
</script>
