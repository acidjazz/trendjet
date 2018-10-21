<template lang="pug">
span
  span Purchased&nbsp;
  strong {{ value.payload.title }}&nbsp;
  span with&nbsp;
  strong: FormatNumber(:value="value.payload.views")
  span &nbsp;views.
</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { FormatNumber },
  props: {
    value: {
      type: Object,
      required: true,
    }
  },
}
</script>
