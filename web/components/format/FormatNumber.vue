<template lang="pug">
span(v-if="text") {{ formatted }}
span.digits(v-else)
  transition(name="digit",mode="out-in",v-for="char in arrayed",:key="char")
    span.digit(:key="char") {{ char }}
</template>

<script>
export default {
  props: {
    format: {
      type: String,
      default: '0,0',
    },
    value: {
      required: true,
    },
    text: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  computed: {
    formatted () {
      if (process.browser) {
        return window.numeral(this.value).format(this.format)
      }
      return this.value
    },

    arrayed () {
      if (this.formatted !== 0) {
        return this.formatted.split('')
      }
      return 0
    }
  },
}
</script>

