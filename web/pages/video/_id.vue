<template lang="pug">
#Video.page
  BreadCrumbs(:crumbs="crumbs")
  section.section
    .container
      p hello
      pre {{ video }}
</template>

<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
export default {
  components: { BreadCrumbs },

  methods: {
    async get () {
      this.video = (await this.$axios.get(`/video/${this.$route.params.id}`)).data.data
      this.crumbs[1].name = this.video.title
    }
  },

  mounted () {
    this.get()
  },

  computed: {
    title () {
      return this.video.title ? this.video.title : ''
    }
  },

  data () {
    return {
      loaded: false,
      video: {},
      crumbs: [
        {
          name: 'My Videos',
          icon: 'youtube',
          to: '/videos',
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
