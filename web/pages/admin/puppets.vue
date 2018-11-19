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
                    span.tag {{ puppet.State }}
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
  },

  mounted () {
    this.get()
  },


  data () {
    return {
      refreshing: false,
      puppets: {},
    }
  }
}
</script>
