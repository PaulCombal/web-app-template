<template>
  <q-btn @click="doLogOut">Logout</q-btn>
</template>

<script>
export default {
  name: "ButtonLogout",
  methods: {
    doLogOut() {
      this.$store.dispatch('auth/logout').catch(console.error)
    }
  }
}
</script>

<style scoped>

</style>
