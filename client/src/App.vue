<template>
  <div id="q-app">
    <router-view/>
  </div>
</template>
<script>

export default {
  name: 'App',
  created: function () {
    // must exec once, and only for web version. Capacitor version will use native
/*    if (!this.$q.platform.is.mobile) {
      const params = {
        client_id: process.env.GOOGLE_CLIENT_ID
      }

      if (!params.client_id) {
        return console.error('params.client_id must be specified.')
      }

      window.gapi.load('auth2', () => {
        window.gapi.auth2.init(params)
      }, (error) => {
        console.error('gapi could not load', error)
      })
    }*/


    // Try loading the user from localStorage
    this.$store.dispatch('auth/resume_login')
  }
}
</script>
