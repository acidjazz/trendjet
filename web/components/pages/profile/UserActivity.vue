<template lang="pug">
.box
  table.table.is-striped.is-fullwidth
    thead
      tr
        th Type
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
            i.mdi.mdi-email(v-if="session.source === 'e-mail'")
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
          span {{ session.location.city }}, {{ session.location.state }}
          span &nbsp;
          a.button.is-small(
            :href="`https://www.google.com/maps/@${session.location.lat},${session.location.lon},15z`"
            target="new",
            )
            span.icon
              i.mdi.mdi-google-maps
          // img(:src="`https://maps.googleapis.com/maps/api/staticmap?size=64x64&zoom=8&center=${session.location.lat},${session.location.lon}&format=png&style=feature:road.highway%7Celement:geometry%7Cvisibility:simplified%7Ccolor:0xc280e9&style=feature:transit.line%7Cvisibility:simplified%7Ccolor:0xbababa&style=feature:road.highway%7Celement:labels.text.stroke%7Cvisibility:on%7Ccolor:0xb06eba&style=feature:road.highway%7Celement:labels.text.fill%7Cvisibility:on%7Ccolor:0xffffff&key=${GOOGLE_API_KEY}`")
        td
          span.has-text-success(disabled,v-if="session.current") This Device
          button.button.is-danger.is-small(
            v-else,
            @click="confirm(session)",
            :class="{'is-loading': revoking === session.token}")
            span Revoke

</template>

<script>
import FormatDate from '@/components/format/FormatDate'
import query from '@/mixins/query'
import envs from '@/mixins/envs'
export default {
  mixins: [ query ],
  components: { FormatDate },
  methods: {
    async get (query) {
      this.sessions = (await this.$axios.get('/session', {params: query})).data
    },

    async confirm (session) {
      this.revoking = session.token
      this.$modal.show({
        title: 'Removing a session',
        body: 'Are you sure you want to remove this session?',
        buttons: [
          {name: 'Yes', class: 'is-primary', action: () => { this.revoke(session) }},
          {name: 'Cancel', action: () => { this.revoking = false }},
        ],
      })
    },

    async revoke (session) {
      let response = await this.$axios.delete(`/session/${session.token}`)
      if (response.data && response.data.data.success) {
          this.$toast.show(response.data.data)
          this.get(this.$route.query)
      }
      this.revoking = false
    },
  },
  mounted () {
    this.get(this.$route.query)
  },
  data () {
    return {
      sessions: {},
      revoking: false,
    }
  },

}
</script>
