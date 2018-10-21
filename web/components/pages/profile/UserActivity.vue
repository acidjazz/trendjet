<template lang="pug">
.timeline.is-centered(v-if="activity.data")
  header.timeline-header
    span.tag.is-medium.is-primary Start
  .timeline-item.is-primary(v-for="item in activity.data")
    .timeline-marker.is-primary.is-icon
      i.mdi(:class="icon(item.action)")
    .timeline-content
      p.heading: FormatDate(:value="item.created_at")
      p(v-if="item.action === 'register'")
        span Created account&nbsp;
        strong {{ item.payload.name }}
      p(v-if="item.action === 'purchase'")
        UserActivityPurchase(:value="item")
      p(v-if="item.action === 'boost'")
        UserActivityBoost(:value="item")
  .timeline-header
    span.tag.is-medium.is-primary End
</template>

<script>
import FormatDate from '@/components/format/FormatDate'
import FormatNumber from '@/components/format/FormatNumber'

import UserActivityBoost from '@/components/pages/profile/UserActivityBoost'
import UserActivityPurchase from '@/components/pages/profile/UserActivityPurchase'
export default {

  components: { FormatDate, FormatNumber, UserActivityBoost, UserActivityPurchase },

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
        case 'boost':
          return 'mdi-rocket'
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
