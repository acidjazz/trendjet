<template lang="pug">
span.digits(:class="this.speed")
  transition(name="slide-fade",mode="out-in",v-for="char in arrayed",:key="char")
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
    speed: {
      default: 'fast'
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

