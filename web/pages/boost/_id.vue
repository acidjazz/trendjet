<template lang="pug">
#Boost.page
  BreadCrumbs(:crumbs="crumbs")
  section.section
    //.container
      pre {{ boost }}
    .container
      .columns.is-multiline
        .column.is-one-third(v-for="shot in shots.data")
          .card
            .card-image
              figure.image
                img(:src="shot.url")
            .card-content
              table.table.is-striped.is-fullwidth
                tr
                  td Created
                  td: FormatDate(:value="shot.created_at")
                tr
                  td Duration
                  td: FormatDuration(:value="shot.duration")
      Paginate(v-if="loaded",:paginate="shots.paginate",:query="query")

</template>

<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
import FormatDuration from '@/components/format/FormatDuration'
import FormatDate from '@/components/format/FormatDate'
import Paginate from '@/components/buttons/Paginate'
export default {
  components: { BreadCrumbs, FormatDuration, FormatDate, Paginate },

  methods: {
    async get (query) {
      this.boost = (await this.$axios.get(`/boost/${this.$route.params.id}`)).data.data
      this.crumbs[1].name = this.boost.video.title
      this.shots = (await this.$axios.get(`/shot/?boost_id=${this.$route.params.id}`, {params: query})).data
      this.loaded = true
    },
    query (params) {
      let query = Object.assign({}, this.$route.query, params)
      this.$router.push({ query: query })
      this.get(query)
    },
  },
  mounted () {
    this.get(this.$route.query)
  },

  computed: {
    title () {
      return this.boost.title ? this.boost.title : ''
    }
  },

  data () {
    return {
      loaded: false,
      boost: {},
      shots: {},
      crumbs: [
        {
          name: 'Boosts',
          icon: 'rocket',
          to: '/boosts',
        },
        {
          name: '..',
          icon: false,
          to: false,
          active: true,
        },
      ]
    }
  }
}
</script>
