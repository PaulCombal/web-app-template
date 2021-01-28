<template>
  <div id="q-app">
    <router-view/>
  </div>
</template>
<script>

export default {
  name: 'App',
  created: function () {
    // Try loading the user from localStorage
    this.$store.dispatch('auth/resume_login')
  }
}
</script>
