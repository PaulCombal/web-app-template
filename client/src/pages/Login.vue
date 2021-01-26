<template>
  <q-page class="flex flex-center">

    <div class="row">
      OLD ASS TOKEN FOR TESTING PURPOSE
      <pre>eyJhbGciOiJSUzI1NiIsImtpZCI6IjI1MmZjYjk3ZGY1YjZiNGY2ZDFhODg1ZjFlNjNkYzRhOWNkMjMwYzUiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiOTA1MDI0NzUxNDAyLTlhN2xwMjdqNGpwaTZqajNhazM1a3VlZ2VkbjJ1M284LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiOTA1MDI0NzUxNDAyLTlhN2xwMjdqNGpwaTZqajNhazM1a3VlZ2VkbjJ1M284LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTAzNjk3MjgxNTUzMTAyMTAyMzY5IiwiZW1haWwiOiJza2ltcGVubnl0ZWFtQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoidEkwaDJpeW5MbHVNQnRJa3d4OFVSUSIsIm5hbWUiOiJTa2ltIFRlYW0iLCJwaWN0dXJlIjoiaHR0cHM6Ly9saDMuZ29vZ2xldXNlcmNvbnRlbnQuY29tLy10TWJxaFF6b3Vjcy9BQUFBQUFBQUFBSS9BQUFBQUFBQUFBQS9BTVp1dWNtU3A5cTJsRURxQ3ZLMnZObnl0ZWh5bUgxUlhnL3M5Ni1jL3Bob3RvLmpwZyIsImdpdmVuX25hbWUiOiJTa2ltIiwiZmFtaWx5X25hbWUiOiJUZWFtIiwibG9jYWxlIjoiZnIiLCJpYXQiOjE2MTA1NTI3OTgsImV4cCI6MTYxMDU1NjM5OCwianRpIjoiMDRjMjBlN2E3NTIxZGU4ZWYyMDU2ZDBhMDdmZGFiMzJiNmVlMmFhNSJ9.scOjIbNB0BPJ_HFrvk6oSWgDQQYDkq4ctEPBO9slyltrnCFasSvGmK4ZG4fcyEf5i7kg2xVLgzKuUxcEeIBfIiTSloOhjEHn1b5YI2v7JNvmzRlZe5xWG89L_ECdcPZ1fTjiEGm5OnAHEJGc0P6N7apZ7pRq4ptLNN4ECwAsrfU2FnxWqvKVb2uctQ2gcTlhN0276Z_bSBaTe4w4UN8ZVUSURBxe7EqN-t-5WwOtQp1D0Y9IDVLgoYjTd8SoO8C1D158IvCk7JF2Z6svP81bTRfwBXbJ7Zu8kVTBhuP5ONQz8jsRYsPgmbebMA9DnDLkmhh89vlhTZ4a2M6ynCvmBQ</pre>
    </div>

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
