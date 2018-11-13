<template lang="pug">
#Video.page
  BreadCrumbs(:crumbs="crumbs")
  br
  .container
    h1.title.is-size-6 Video Logs
    .columns
      .column.is-one-third
        ViewLog(v-if="video.logs",:logs="video.logs")
  br
  .container
    h1.title.is-size-6 Video Boosts
    BoostList(v-if="video.boosts", :boosts="video.boosts")
</template>

<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
import ViewLog from '@/components/video/ViewLog'
import VideoCard from '@/components/video/VideoCard'
import BoostList from '@/components/boost/BoostList'
export default {
  components: { BreadCrumbs, VideoCard, ViewLog, BoostList },

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
