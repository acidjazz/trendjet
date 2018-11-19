<template lang="pug">
#Puppets.page-admin
  .container
    .box
      .tags.has-addons
        span.tag.ani-zoom-in.is-success live
        span.tag.ani-zoom-in.is-info(v-if="refreshing") refreshing
        span.tag.ani-zoom-in.has-text-grey(v-else) refreshing
      .columns.is-multiline
        .column.is-one-quarter(v-for="puppet in puppets")
          .card
            header.card-header
              p.card-header-title {{ puppet.InstanceId }}
            .card-content
              .field.is-grouped.is-grouped-multiline
                .control
                  .tags.has-addons
                    span.tag.is-dark state
                    span.tag(:class="state(puppet.State)") {{ puppet.State }}
                .control
                  .tags.has-addons
                    span.tag.is-dark runtime
                    span.tag: FormatDuration(:value="puppet.Runtime")
</template>

<script>
import FormatDuration from '@/components/format/FormatDuration'
export default {
  components: { FormatDuration },
  middleware: ['is-admin'],

  methods: {
    async get () {
      this.puppets = (await this.$axios.get('/puppet')).data.data
    },
    state (state) {
      if (state === 'pending') {
        return 'is-warning'
      }
      if (state === 'stopped') {
        return 'is-warning'
      }
      if (state === 'stopping') {
        return 'is-warning'
      }
      if (state === 'running') {
        return 'is-success'
      }
      if (state === 'shutting-down') {
        return 'is-danger'
      }

      return 'is-danger'
    },

    async refresh () {
      this.refreshing = true
      await this.get(this.$route.query)
      setTimeout( () => this.refreshing = false, 1000)
    },
  },

  mounted () {
    this.get()
    if (process.browser) {
      if (this.interval === false) {
        this.interval = setInterval(this.refresh, 10000)
      }
    }
  },

  async destroyed () {
    if (process.browser) {
      clearInterval(this.interval)
      this.interval = false
    }
  },


  data () {
    return {
      interval: false,
      refreshing: false,
      puppets: {},
    }
  }
}
</script>
