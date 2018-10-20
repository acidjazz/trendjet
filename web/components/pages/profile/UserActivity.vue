<template lang="pug">
.timeline.is-centered
  header.timeline-header
    span.tag.is-medium.is-primary Start
  .timeline-item.is-primary(v-for="item in activity.data")
    .timeline-marker.is-primary.is-icon
      i.mdi(:class="icon(item.action)")
    .timeline-content
      p.heading: FormatDate(:value="item.created_at")
      p(v-if="item.action == 'register'")
        | Created account&nbsp;
        strong {{ item.payload.name }}
      p(v-if="item.action == 'purchase'")
        | Purchased&nbsp;
        strong {{ item.payload.title }}&nbsp;
        | with&nbsp;
        strong: FormatNumber(:value="item.payload.views")
        | &nbsp;views.

  pre
    code {{ this.activity }}

</template>

<script>
import FormatDate from '@/components/format/FormatDate'
import FormatNumber from '@/components/format/FormatNumber'
export default {

  components: { FormatDate, FormatNumber },

  methods: {
    async get (query) {
      this.activity = (await this.$axios.get('/activity', {params: query})).data
    },
    icon (action) {
      switch (action) {
        case 'register':
          return 'mdi-account'
          break;
        case 'purchase':
          return 'mdi-currency-usd'
          break;
      }
    },
  },

  mounted () {
    this.get(this.$route.query)
  },


  data () {
    return {
      activity: {},
    }
  },
}
</script>
