<template lang="pug">
.progress-bar(:style="`height: ${this.height}px;`")
  .progress-bar-gradient(:class="{'on': on, 'off': !on}")
  .progress-bar-complete(:style="`width: ${this.complete}%;`")
</template>

<script>
export default {
  props: {
    on: {
      required: true,
      type: Boolean,
    },
    height: {
      required: false,
      type: Number,
      default: 40,
    },
    total: {
      required: true,
      type: Number,
    },
    progress: {
      required: true,
      type: Number,
    }
  },

  computed: {
    complete () {
      return this.progress / this.total * 100
    },
    remaining () {
      return 100 - this.complete
    },
  },
}
</script>
