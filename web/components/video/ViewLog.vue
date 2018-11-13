<template lang="pug">
.box
  table.table.is-striped.is-fullwidth
    thead
      tr
        th Views
        th Date
    tbody
      tr(v-for="log in logs")
        td: FormatNumber(:value="log.views")
        td: FormatDate(:value="log.created_at")

</template>

<script>
import FormatDate from '@/components/format/FormatDate'
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { FormatDate, FormatNumber },
  props: {
    logs: {
      type: Array,
      required: true,
    }
  }
}
</script>
