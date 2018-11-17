<template lang="pug">
#Boosts.page
  BreadCrumbs(:crumbs="crumbs")
    span.tag.ani-zoom-in.is-info.is-pulled-right(v-if="refreshing") refreshing
  section.section(v-if="boosts.data")
    .container
      BoostList(:boosts="boosts.data")
      Paginate(v-if="loaded",:paginate="boosts.paginate",:query="query")
</template>

<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
import BoostList from '@/components/boost/BoostList'
import Paginate from '@/components/buttons/Paginate'
export default {
  components: { BreadCrumbs, BoostList, Paginate },

  methods: {
    visibility () {
      this.visible = document.visibilityState === 'visible'
    },

    async get (query) {
      this.boosts = (await this.$axios.get('/boost', { params: query })).data
      this.loaded = true
    },

    query (params) {
      let query = Object.assign({}, this.$route.query, params)
      this.$router.push({ query: query })
      this.get(query)
    },

    async refresh () {
      if (!this.visible) return true
      this.refreshing = true
      this.boosts = (await this.$axios.get('/boost', { params: this.$route.query })).data
      setTimeout( () => this.refreshing = false, 1000)
    },

  },

  async mounted () {
    await this.get(this.$route.query)
    if (process.browser) {
      if (this.interval === false) {
        this.interval = setInterval(this.refresh, 5000)
      }
      window.addEventListener('visibilitychange', this.visibility)
      this.visibility()
    }
  },

  async destroyed () {
    if (process.browser) {
      this.interval === false
      window.addEventListener('visibilitychange', this.visibility)
    }
  },

  data () {
    return {
      visible: false,
      interval: false,
      refreshing: false,
      loaded: false,
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
