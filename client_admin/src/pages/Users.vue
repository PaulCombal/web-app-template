<template>
  <q-page class="q-pa-md">
    <div class="row">
      <div class="col">
        <h6>Users</h6>
        <div>Make yourself at home and make your users overview with cool stats here</div>
      </div>
    </div>
    <div class="row q-mt-md">
      <div class="col">
        <q-btn to="/users/listing" icon="list" label="Go to listing"></q-btn>
      </div>
    </div>
  </q-page>
</template>

<script>

export default {
  name: 'PageUsers',
  components: {},
}
</script>
