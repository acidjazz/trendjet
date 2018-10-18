<template lang="pug">
.box
  table.table.is-striped.is-fullwidth
    thead
      tr
        th(v-if="admin") User
        th Type
        th Created
        th Updated
        th Device
        th Location
        th Actions
    tbody
      tr(v-for="session in sessions.data")
        td(v-if="admin")
          article.media
            figure.media-left
              p.image.is-24x24
                img.avatar.object-cover(:src="session.user.avatar")
            .media-content {{ session.user.name }}
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
          a.button.is-small.is-text(
            :href="`https://www.google.com/maps/@${session.location.lat},${session.location.lon},15z`"
            target="new")
            span.icon
              i.mdi.mdi-google-maps
        td
          button.button.is-small(disabled,v-if="session.current").is-success.is-outlined This Device
          button.button.is-small.tooltip(
            v-else,
            data-tooltip="Disconnect Device",
            @click="confirm(session)",
            :class="{'is-loading': revoking === session.token}")
            span Disconnect

</template>

<script>

import FormatDate from '@/components/format/FormatDate'
import query from '@/mixins/query'
import envs from '@/mixins/envs'

export default {
  props: {
    admin: {
      type: Boolean,
      required: false,
      default: false,
    }
  },
  mixins: [ query ],
  components: { FormatDate },
  methods: {
    async get (query) {
      if (this.admin) {
        this.sessions = (await this.$axios.get('/session/?all=1', {params: query})).data
      } else {
        this.sessions = (await this.$axios.get('/session', {params: query})).data
      }
    },

    async confirm (session) {
      this.revoking = session.token
      this.$modal.show({
        title: 'Disconnect Device',
        body: 'Are you sure you want to disconnect this device?',
        closed: () => this.revoking = false,
        buttons: [
          {name: 'Disconnect', class: 'is-danger', action: () => { this.revoke(session) }},
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
