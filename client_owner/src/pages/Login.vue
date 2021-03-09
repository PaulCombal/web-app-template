<template>
  <q-page class="q-pa-md">
    <div class="row text-center">
      <div class="col">
        <h6>Privileged area</h6>
      </div>
    </div>
    <div class="row text-center justify-center">
      <div class="col-xs-12 col-md-6">
        <q-card>
          <q-card-section>
            <div class="text-h6">Login here</div>
            <div class="text-subtitle2">Your account must be elevated</div>
          </q-card-section>

          <q-separator></q-separator>

          <q-card-section>
            <GoogleLoginBtn v-if="!$store.getters['auth/loggedIn']"></GoogleLoginBtn>
            <ButtonLogout v-else></ButtonLogout>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import GoogleLoginBtn from "components/GoogleLoginBtn";
import ButtonLogout from "components/ButtonLogout";

export default {
  name: 'PageLogin',
  components: {ButtonLogout, GoogleLoginBtn},
  computed: {
    loggedIn() {
      return this.$store.getters["auth/loggedIn"]
    }
  },
  watch: {
    loggedIn(n,o) {
      if (n) {
        this.$router.push('/')
      }
    }
  },
  mounted() {
    if (this.$store.getters['auth/loggedIn']) {
      this.$router.push('/')
    }
  }
}
</script>
