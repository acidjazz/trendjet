<template lang="pug">
.buttons.has-addons
  button.button.is-primary(@click="boost(selected)")
    span.icon
      i.mdi.mdi-rocket
    span Boost Selected (
    FormatNumber(:value="selected.length")
    span )
  button.button.ani-slide-in-right(@click="clear")
    span.icon
      i.mdi.mdi-close
</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { FormatNumber },
  props: {
    selected: {
      required: true,
      type: Array,
    },
    clear: {
      required: true,
      type: Function,
    },
    boost: {
      required: true,
      type: Function,
    },
  },
}
</script>
