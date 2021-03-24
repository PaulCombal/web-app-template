<template>
  <Fragment>
    <q-table
      :loading="loading"
      :columns="columns"
      :row-key="rowKey"
      :pagination.sync="pagination"
      :title="title"
      :visible-columns="visible_columns"
      :data="data"
      @request="onRequest"
    >

      <template v-slot:top="props">
        <div class="col-2 q-table__title">{{ title }}</div>

        <q-space/>

        <q-select
          v-model="visible_columns"
          multiple
          borderless
          dense
          options-dense
          :display-value="$q.lang.table.columns"
          emit-value
          map-options
          :options="columns"
          option-value="name"
          options-cover
          style="min-width: 150px"
        />

        <!-- might work with Vue3 -->
        <!--<q-btn
          flat round dense
          :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
          @click="props.toggleFullscreen"
          class="q-ml-md"
        />-->
      </template>

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
        <q-card-section class="flex">
          <q-avatar icon="signal_wifi_off" color="primary" text-color="white"/>
          <div style="flex: 1" class="q-ml-sm">
            <div>{{ curr_action.confirm.text }}</div>
            <!-- TODO put subtext -->
          </div>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancel" color="primary" v-close-popup/>
          <q-btn flat label="Confirm" color="primary" @click="onConfirmClickAction" v-close-popup/>
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
    preventLoading: {
      type: Boolean,
      default: false
    },
    url: {
      type: String,
      required: true
    },
    urlQuery: {
      type: Object
    },
    /**
     * {
          name: 'Email',
          label: 'User Email',
          field: row => row.email,
          format: val => `${val}`
        },
     */
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
    },
    defaultVisibleColumns: {
      type: Array
    }
  },
  data() {
    return {
      loading: true,
      data: [],
      confirm: false,
      visible_columns: [],
      curr_action: {
        confirm: {
          text: null
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
    async onRequest(props = {pagination: this.pagination}) {
      if (this.preventLoading) return;
      this.loading = true
      const {page, rowsPerPage, sortBy, descending} = props.pagination
      const s = (page - 1) * rowsPerPage
      const n = rowsPerPage
      const res = await this.$axios.get(this.fullQueryURL, {params: {s, n}}).then(r => r.data)
      this.pagination.page = page
      this.pagination.rowsPerPage = rowsPerPage
      this.pagination.rowsNumber = res.count
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
    urlQueryToStr() {
      if (!this.urlQuery || Object.keys(this.urlQuery).length === 0) return ''
      return '?' + (new URLSearchParams(this.urlQuery)).toString()
    },
    fullQueryURL() {
      return this.url + this.urlQueryToStr
    },
    onConfirmClickAction() {
      const refreshFn = () => this.onRequest()
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

    this.visible_columns = this.defaultVisibleColumns || this.columns.map(c => c.name)
    this.onRequest()
  },
  watch: {
    urlQuery() {
      this.onRequest()
    }
  }
}
</script>
