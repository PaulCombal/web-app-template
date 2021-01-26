<template>
  <q-page class="flex flex-center">
    <img
      alt="Quasar logo"
      src="~assets/quasar-logo-full.svg"
    >

    <div class="row">
      <div v-if="$store.getters['auth/loggedIn']">Hello {{ $store.getters['auth/user'].email }}</div>
      <div v-else>Unconnected</div>
    </div>
    <div class="row">
      <GoogleLoginBtn @success="log"></GoogleLoginBtn>
      <q-btn @click="askProfile">Log test</q-btn>
      <ButtonLogout></ButtonLogout>
    </div>
  </q-page>
</template>

<script>
import GoogleLoginBtn from "components/GoogleLoginBtn";
import ButtonLogout from "components/ButtonLogout";

export default {
  name: 'PageIndex',
  components: {ButtonLogout, GoogleLoginBtn},
  methods: {
    log() {

    },
    askProfile() {
      this.$axios.get("profile").then(r => r.data).then(console.log);
    }
  }
}
</script>
