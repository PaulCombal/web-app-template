<template>
  <Fragment>
    <Fragment v-for="c of schema" :key="c.key">
      <q-input
          v-if="c.tag === 'input'"
          v-model="model[c.model]"
          :filled="c.props.filled"
          :label="c.props.label"
          :hint="c.props.hint"
          :lazy-rules="c.props.lazyRules"
          :rules="c.props.rules"
          :type="c.props.type"
          @input="$emit('input', model)"
        ></q-input>
    </Fragment>
  </Fragment>
</template>

<script>
import {Fragment} from "vue-fragment";

export default {
  name: "FormGenerator",
  components: {Fragment},
  data() {
    return {

    }
  },
  /**
   * The model is an object having for keys the field value
   */
  model: {
    prop: 'model',
    event: 'input'
  },
  props: {
    schema: {
      /**
       * Array of objects
       * keys
       * tag: String : 'input' only is supported as of now
       * key: String : Unique identifier for the for loop
       * model: String: the key name given for the v-model object
       */
      type: Array,
      required: true
    },
    model: {}
  },
  methods: {

  },
  mounted() {

  }
}
</script>

<style scoped>

</style>