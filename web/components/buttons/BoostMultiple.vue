<template lang="pug">
button.button(v-if="selected.length < 1",@click="all") Select All
.buttons.has-addons(v-else)
  button.button.is-primary.ani-slide-in-right(@click="boost(selected)")
    span.icon
      i.mdi.mdi-rocket
    span Boost Selected (
    FormatNumber(:value="selected.length")
    span )
  button.button.ani-slide-in-right.delay-1(@click="clear")
    span.icon
      i.mdi.mdi-close
</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { FormatNumber },
  props: {
    all: {
      type: Function,
      required: true,
    },
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
