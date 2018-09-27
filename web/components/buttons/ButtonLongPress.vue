<template lang="pug">
button.button.button-longpress.is-fullwidth(
  @mousedown="start",
  @touchstart="start",
  @touchend="end")
  .button-longpress-inner(:style="`width: ${width}%`",:class="`theme-${theme}`"): span {{ label }}
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: true,
    },
    action: {
      type: Function,
      required: true,
    },
    theme: {
      type: String,
      required: false,
      default: 'dark',
    }
  },

  methods: {
    start () {
      this.interval = setInterval(this.count, 10)
    },
    end () {
      clearInterval(this.interval)
      this.width = 0
    },
    count () {
      this.width = this.width+1
      if (this.width >= 100) {
        this.end()
        this.action()
      }
    },
  },

  created () {
    document.addEventListener('mouseup', this.end)
  },

  destroyed () {
    document.removeEventListener('mouseup', this.end)
  },

  data () {
    return {
      interval: {},
      width: 0,
    }
  }
}
</script>
