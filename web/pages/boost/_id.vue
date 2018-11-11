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
</template>

<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
export default {
  components: { BreadCrumbs },

  methods: {
    async get () {
      this.boost = (await this.$axios.get(`/boost/${this.$route.params.id}`)).data.data
      this.crumbs[1].name = this.boost.video.title
      this.shots = (await this.$axios.get(`/shot/?boost_id=${this.$route.params.id}`)).data
    }
  },

  mounted () {
    this.get()
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
