<template>
  <q-page class="q-pa-md">
    <div class="row">
      <div class="col">
        Site moderator / privileged user
      </div>
    </div>
    <div class="row">
      <div v-if="$store.getters['auth/loggedIn']">Hello {{ $store.getters['auth/user'].email }}</div>
      <div v-else>Unconnected</div>
    </div>
    <div class="row">
      <GoogleLoginBtn v-if="!$store.getters['auth/loggedIn']"></GoogleLoginBtn>
      <ButtonLogout v-else></ButtonLogout>
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
  }
}
</script>
