<template lang="pug">
#Boosts.page
  BreadCrumbs(:crumbs="crumbs")
  section.section
    .container
      pre
        code {{ boosts.data }}
</template>

<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
export default {
  components: { BreadCrumbs },

  methods: {
    async get (query) {
      this.boosts = (await this.$axios.get('/boost', { params: query })).data
    },
  },

  mounted () {
    this.get(this.$route.query)
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
