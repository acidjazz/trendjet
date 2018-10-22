<template lang="pug">
#Boosts.page
  BreadCrumbs(:crumbs="crumbs")
  section.section(v-if="boosts.data")
    .container
      BoostList(:boosts="boosts.data")
</template>

<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
import BoostList from '@/components/boost/BoostList'
export default {
  components: { BreadCrumbs, BoostList },

  methods: {
    async get (query) {
      this.boosts = (await this.$axios.get('/boost', { params: query })).data
    },
  },

  async mounted () {
    await this.get(this.$route.query)
  },

  data () {
    return {
      boosts: {},
      crumbs: [
        {
          name: 'Boosts',
          icon: 'rocket',
          to: '/boosts',
          active: true,
        },
      ]
    }
  }
}
</script>
