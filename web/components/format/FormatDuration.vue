<template lang="pug">
time.tooltip(:data-tooltip="value | human") {{ value | duration }}
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      required: true,
    },
  },

  filters: {
    duration: function (value) {
      if (process.browser) {
        return window.moment(window.moment.duration(value).as('milliseconds')).format('m\\m s\\s')
      }
      return value
    },
    human: function(value) {
      if (process.browser) {
        return window.moment.duration(value).humanize()
      }
      return value
    },

  },
}
</script>
