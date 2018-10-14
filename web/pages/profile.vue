<template lang="pug">
#Profile.page
  section.section
    h2.subtitle Activity
    .box
      table.table.is-stripe.is-fullwidth
        thead
          tr
            th Created
            th Updated
            th Device
            th Location
            th Actions
        tbody(v-for="session in sessions.data")
          tr
            td: FormatDate(:value="session.created_at")
            td: FormatDate(:value="session.updated_at")
            td
              span.icon(v-if="session.device.mobile")
                i.mdi.mdi-cellphone
              span.icon(v-if="session.device.desktop")
                i.mdi.mdi-monitor

              span.icon(v-if="session.device.platform.includes('macOS')")
                i.mdi.mdi-apple
              span.icon(v-if="session.device.platform.includes('Windows')")
                i.mdi.mdi-windows

              span.icon(v-if="session.device.browser.includes('Chrome')")
                i.mdi.mdi-google-chrome
              span.icon(v-if="session.device.browser.includes('Safari')")
                i.mdi.mdi-apple-safari
            td
              div {{ session.location.city }}, {{ session.location.state }}
              // img(:src="`https://maps.googleapis.com/maps/api/staticmap?size=64x64&zoom=8&center=${session.location.lat},${session.location.lon}&format=png&style=feature:road.highway%7Celement:geometry%7Cvisibility:simplified%7Ccolor:0xc280e9&style=feature:transit.line%7Cvisibility:simplified%7Ccolor:0xbababa&style=feature:road.highway%7Celement:labels.text.stroke%7Cvisibility:on%7Ccolor:0xb06eba&style=feature:road.highway%7Celement:labels.text.fill%7Cvisibility:on%7Ccolor:0xffffff&key=${GOOGLE_API_KEY}`")
            td
              span.has-text-success(disabled,v-if="session.current") THIS DEVICE
              button.button.is-danger.is-small(v-else)
                span Remove
</template>

<script>
import FormatDate from '@/components/format/FormatDate'
import query from '@/mixins/query'
import envs from '@/mixins/envs'
export default {
  mixins: [ query, envs ],
  components: { FormatDate },
  methods: {
    async get (query) {
      this.sessions = (await this.$axios.get('/sessions', {params: query})).data
    }
  },
  mounted () {
    this.get(this.$route.query)
    console.log(this.GOOGLE_API_KEY)
  },
  data () {
    return {
      sessions: {},
    }
  },
}
</script>
