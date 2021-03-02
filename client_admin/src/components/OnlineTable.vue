<template>
  <Fragment>
    <q-table
      :loading="loading"
      :columns="columns"
      :row-key="rowKey"
      :pagination.sync="pagination"
      :title="title"
      @request="onRequest"
      :data="data"
    >

      <template v-if="rowsExpand" v-slot:header="props">
        <q-tr :props="props">
          <q-th auto-width/>
          <q-th
            v-for="col in props.cols"
            :key="col.name"
            :props="props"
          >
            {{ col.label }}
          </q-th>
        </q-tr>
      </template>

      <template v-if="rowsExpand" v-slot:body="props">
        <q-tr :props="props">
          <q-td auto-width>
            <q-btn size="sm" color="accent" round dense @click="props.expand = !props.expand"
                   :icon="props.expand ? 'remove' : 'add'"/>
          </q-td>
          <q-td
            v-for="col in props.cols"
            :key="col.name"
            :props="props"
          >
            {{ col.value }}
          </q-td>
        </q-tr>
        <q-tr v-show="props.expand" :props="props">
          <q-td colspan="100%">
            <q-btn-group>
              <q-btn :key="a.tooltip" :icon="a.icon" v-for="a in actions" @click="handleActionClick(a, props.row)">
                <q-tooltip v-if="a.tooltip">{{ a.tooltip }}</q-tooltip>
              </q-btn>
            </q-btn-group>
          </q-td>
        </q-tr>
      </template>
    </q-table>

    <q-dialog v-model="confirm" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="signal_wifi_off" color="primary" text-color="white" />
          <span class="q-ml-sm">{{ curr_action.confirm.text }}</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancel" color="primary" v-close-popup />
          <q-btn flat label="Confirm" color="primary" @click="onConfirmClickAction" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </Fragment>
</template>

<script>
import {Fragment} from 'vue-fragment'
export default {
  name: 'OnlineTable',
  components: {Fragment},
  props: {
    url: {
      type: String,
      required: true
    },
    columns: {
      type: Array,
      required: true
    },
    title: {
      type: String,
      default: 'Listing'
    },
    /** action is an array of objects
     * keys:
     * type: 'navigate|function|confirm}'
     * to: function(row): string (if navigate, the url)
     * fn: function(row): any (if type is function or confirm)
     * refresh: Boolean: (refresh the table after fn is executed, but fn must return promise-like)
     * icon: string (the icon from material icon set)
     * tooltip: string (mandatory as it is used as a key, describe what this does)
     * confirm: object
     * keys:
     * text: String: text to show on the confirmation box
     * TODO icons, subtext, buttonText, and similar
     */
    actions: {
      type: Array
    },
    rowKey: {
      type: String,
      default: 'id'
    },
    paginationOverride: {
      type: Object
    }
  },
  data() {
    return {
      loading: true,
      data: [],
      confirm: false,
      curr_action: {
        confirm: {
          text:null
        }
      },
      curr_row: null,
      pagination: {
        page: 1,
        rowsPerPage: 5,
        rowsNumber: 10
      },
    }
  },
  methods: {
    async onRequest(props) {
      this.loading = true
      const {page, rowsPerPage, sortBy, descending} = props.pagination
      const s = (page - 1) * rowsPerPage
      const n = rowsPerPage
      const res = await this.$axios.get(this.url, {params: {s, n}}).then(r => r.data)
      this.pagination.page = page
      this.pagination.rowsPerPage = rowsPerPage
      this.pagination.rowsNumber = res.total
      this.loading = false
      this.data = res.data
    },
    handleActionClick(action, row) {
      switch (action.type) {
        case 'navigate':
          this.$router.push(action.to(row))
          break;
        case 'function':
          action.fn(row)
          break
        case 'confirm':
          this.curr_action = action
          this.curr_row = row
          this.confirm = true
          break

        default:
          alert('Unknown action type. Please tell a developer')
      }
    }
  },
  computed: {
    rowsExpand() {
      return this.actions && this.actions.length
    },
    onConfirmClickAction() {
      const refreshFn = () => this.onRequest({pagination: this.pagination})
      const catchFn = () => this.$q.notify({
        type: 'negative',
        message: 'Something went wrong, tell a dev how you broke everything!'
      })
      const thenFn = () => this.$q.notify({
        type: 'positive',
        message: 'All done!'
      })
      if (this.curr_action.refresh) {
        return () => this.curr_action.fn(this.curr_row).then(thenFn).then(refreshFn).catch(catchFn)
      }
      return () => this.curr_action.fn(this.curr_row).catch(catchFn).then(thenFn)
    }
  },
  mounted() {
    if (this.paginationOverride) {
      this.pagination = {...this.pagination, ...this.paginationOverride}
    }

    this.onRequest({
      pagination: this.pagination
    })
  }
}
</script>
