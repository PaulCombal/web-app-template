<template>
  <q-page class="q-pa-md">

    <div class="row">
      <div class="col">
        <OnlineTable
          :columns="columns"
          :actions="actions"
          title="User listing"
          url="user"
        ></OnlineTable>
      </div>
    </div>

    <div class="row q-mt-md">
      <div class="col">
        <q-btn to="/users" label="To users overview"></q-btn>
      </div>
    </div>
  </q-page>
</template>

<script>
import OnlineTable from "components/OnlineTable";
export default {
  name: 'PageUserListing',
  components: {OnlineTable},
  data() {
    return {
      columns: [
        {
          name: 'ID',
          required: true,
          label: 'User ID',
          align: 'left',
          field: row => row.id,
          format: val => `${val}`
        },
        {
          name: 'Email',
          required: true,
          label: 'User Email',
          field: row => row.email,
          format: val => `${val}`
        },
      ],
      actions: [
        {
          type: 'navigate',
          to: row => `/user/${row.id}`,
          icon: 'face',
          tooltip: 'Go to user profile'
        },
        {
          type: 'function',
          fn: row => alert(row.id),
          icon: 'info',
          tooltip: 'This is a demo'
        },
        {
          type: 'confirm',
          confirm: {
            text: 'Are you sure you want to delete this user? There is no going back.'
          },
          fn: this.deleteUser,
          refresh: true,
          icon: 'delete',
          tooltip: 'Delete user'
        }
      ]
    }
  },
  methods: {
    deleteUser(row) {
      return this.$axios.delete(`user/${row.id}`)
    }
  }
}
</script>
