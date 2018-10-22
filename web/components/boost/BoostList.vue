<template lang="pug">
.columns.is-multiline
  .column.is-one-third(
    v-for="boost, index in boosts"
    :key="index")
    BoostCard(:boost="boost")
</template>

<script>
import BoostCard from '@/components/boost/BoostCard'
export default {
  components: { BoostCard },
  props: {
    boosts: {
      type: Array,
      required: true,
    }
  }
}
</script>
