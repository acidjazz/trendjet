<template lang="pug">
#Boosts.page
  BreadCrumbs(:crumbs="crumbs")
    .tags.has-addons.is-pulled-right(v-if="actives")
      span.tag.ani-zoom-in.is-success live
      span.tag.ani-zoom-in.is-info(v-if="refreshing") refreshing
      span.tag.ani-zoom-in.has-text-grey(v-else) refreshing
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
      this.actives = false
      for (let boost of this.boosts.data) {
        if (['active','pending'].indexOf(boost.status) !== -1) {
          this.actives = true
        }
      }

      if (this.interval !== false && this.actives === false) {
        clearInterval(this.interval)
        this.interval = false
      }

    },

    query (params) {
      let query = Object.assign({}, this.$route.query, params)
      this.$router.push({ query: query })
      this.get(query)
    },

    async refresh () {
      if (!this.visible) return true
      this.refreshing = true
      await this.get(this.$route.query)
      setTimeout( () => this.refreshing = false, 1000)
    },

  },

  async mounted () {
    await this.get(this.$route.query)
    if (process.browser) {

      if (this.interval === false && this.actives === true) {
        this.interval = setInterval(this.refresh, 5000)
      }

      window.addEventListener('visibilitychange', this.visibility)
      this.visibility()
    }
  },

  async destroyed () {
    if (process.browser) {
      clearInterval(this.interval)
      this.interval = false
      window.addEventListener('visibilitychange', this.visibility)
    }
  },

  data () {
    return {
      visible: false,
      interval: false,
      refreshing: false,
      loaded: false,
      actives: false,
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
