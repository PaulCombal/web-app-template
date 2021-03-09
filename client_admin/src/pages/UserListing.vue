<template>
  <q-page class="q-pa-md">

    <div class="row">
      <div class="col">
        <OnlineTable
          :columns="columns"
          :default-visible-columns="visible_columns"
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
      visible_columns: ['ID', 'Email'],
      columns: [
        {
          name: 'ID',
          label: 'User ID',
          align: 'left',
          field: row => row.id,
          format: val => `${val}`
        },
        {
          name: 'Email',
          label: 'User Email',
          field: row => row.email,
          format: val => `${val}`
        },
        {
          name: 'Roles',
          label: 'Roles',
          field: row => row.roles,
          format: val => `${val.join(', ')}`
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
        },
        {
          type: 'confirm',
          confirm: {
            text: 'Are you sure you want to ban this user? He will still be able to login, but the main actions will be blocked.'
          },
          fn: this.banUser,
          refresh: true,
          icon: 'gavel',
          tooltip: 'Ban user'
        }
      ]
    }
  },
  methods: {
    deleteUser(row) {
      return this.$axios.delete(`user/${row.id}`)
    },
    banUser(row) {
      return this.$axios.put(`user/ban/${row.id}`)
    }
  }
}
</script>
