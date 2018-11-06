<template lang="pug">
.field.is-grouped.is-grouped-multiline
    .control
      .tags.has-addons
        span.tag.is-dark status
        span.tag(:class="type") {{ status }}

</template>

<script>
export default {
  props: {
    status: {
      required: true,
      type: String,
    },
  },

  computed: {
    type () {
      if (this.status == 'pending') return 'is-light'
      if (this.status == 'active') return 'is-success'
      if (this.status == 'complete') return 'is-success'
    },
  },
}
</script>
