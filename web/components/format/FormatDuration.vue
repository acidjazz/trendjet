<template lang="pug">
time.tooltip(:data-tooltip="value | human")
  span: FormatNumber(:value="minutes")
  span m
  span &nbsp;
  span: FormatNumber(:value="seconds")
  span s
</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { FormatNumber },
  props: {
    value: {
      type: String,
      required: true,
    },
  },

  computed: {
    seconds: function () {
      if (process.browser) {
        return window.moment.duration(this.value).as('seconds') - (this.minutes*60)
      }
      return value
    },
    minutes: function () {
      if (process.browser) {
        return Math.floor(window.moment.duration(this.value).as('minutes'))
      }
      return value
    },
  },
  filters: {
    human: function(value) {
      if (process.browser) {
        return window.moment.duration(value).humanize()
      }
      return value
    },

  },
}
</script>
