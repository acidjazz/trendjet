<template lang="pug">
#Profile.page
  section.section
    .container
      h2.subtitle Activity
      .box
        table.table.is-stripe.is-fullwidth
          thead
            tr
              th Source
              th Created
              th Updated
              th Device
              th Location
              th Actions
          tbody(v-for="session in sessions.data")
            tr
              td
                span.icon
                  i.mdi.mdi-facebook(v-if="session.source === 'facebook'")
                  i.mdi.mdi-google(v-if="session.source === 'google'")
                  i.mdi.mdi-email(v-if="session.source === 'email'")
              td: FormatDate(:value="session.created_at")
              td: FormatDate(:value="session.updated_at")
              td
                span.icon.tooltip(:data-tooltip="session.device.platform")
                  i.mdi.mdi-cellphone(v-if="session.device.mobile")
                  i.mdi.mdi-monitor(v-if="session.device.desktop")

                span.icon.tooltip(:data-tooltip="session.device.platform")
                  i.mdi.mdi-apple(v-if="session.device.platform.includes('macOS')")
                  i.mdi.mdi-windows(v-if="session.device.platform.includes('Windows')")
                  i.mdi.mdi-android(v-if="session.device.platform.includes('Android')")
                  i.mdi.mdi-ios(v-if="session.device.platform.includes('iOS')")

                span.icon.tooltip(:data-tooltip="session.device.browser")
                  i.mdi.mdi-google-chrome(v-if="session.device.browser.includes('Chrome')")
                  i.mdi.mdi-apple-safari(v-if="session.device.browser.includes('Safari')")
                  i.mdi.mdi-internet-explorer(v-if="session.device.browser.includes('Internet Explorer')")
              td
                div {{ session.location.city }}, {{ session.location.state }}
                // img(:src="`https://maps.googleapis.com/maps/api/staticmap?size=64x64&zoom=8&center=${session.location.lat},${session.location.lon}&format=png&style=feature:road.highway%7Celement:geometry%7Cvisibility:simplified%7Ccolor:0xc280e9&style=feature:transit.line%7Cvisibility:simplified%7Ccolor:0xbababa&style=feature:road.highway%7Celement:labels.text.stroke%7Cvisibility:on%7Ccolor:0xb06eba&style=feature:road.highway%7Celement:labels.text.fill%7Cvisibility:on%7Ccolor:0xffffff&key=${GOOGLE_API_KEY}`")
              td
                span.has-text-success(disabled,v-if="session.current") This Device
                button.button.is-danger.is-small(v-else)
                  span Revoke
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
