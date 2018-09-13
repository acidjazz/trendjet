<template lang="pug">
time.tooltip(
  :datetime="value",
  :data-tooltip="value | format('dddd, MMM Do, YYYY hh:mm:ss a')"
  ) {{ value | fromNow }}
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
    format: function (date, format) {
      if (process.browser) {
        return window.moment.utc(date).local().format(format)
      }
      return date
    },
    fromNow: function (date) {
      if (process.browser) {
        return window.moment.utc(date).local().fromNow()
      }
      return date
    },
  },

}

</script>
