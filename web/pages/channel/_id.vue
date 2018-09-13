<template lang="pug">
#Channel.page
  section.section
    .container
      .level
        .level-left
          .level-item.ani-slide-in-right(v-if="loaded")
            strong: FormatNumber(:value="results.totalResults")
            | &nbsp;videos for&nbsp;
            strong {{ results.channelTitle }}
        .level-right
          .level-item
            nuxt-link.button(to="/videos").ani-slide-in-left
              span.icon
                i.mdi.mdi-video
              span My Videos
          .level-item
            .buttons.has-addons
              button.button.ani-slide-in-left.delay-1(
                :disabled="results.prevPageToken == null || !loaded"
                @click="prev")
                span.icon
                  i.mdi.mdi-arrow-left
                span Previous Page
              button.button.ani-slide-in-left.delay-2(
                :disabled="results.nextPageToken == null || !loaded",
                @click="next")
                span Next Page
                span.icon
                  i.mdi.mdi-arrow-right

      VideoList(v-if="loaded",:videos="results.videos",:channel="true")
</template>

<script>
import VideoList from '@/components/video/VideoList'
import FormatNumber from '@/components/format/FormatNumber'
export default {

  components: { VideoList, FormatNumber },

  methods: {
    next () {
      this.page++
      this.get(this.results.nextPageToken)
    },
    prev () {
      this.page--
      this.get(this.results.prevPageToken)
    },
    async get (pageToken) {
      this.loaded = false
      delete this.results.videos
      this.results = (await this.$axios.get(`/youtube/channel/${this.$route.params.id}`, {params: {pageToken: pageToken}})).data.data
      this.loaded = true
    },
  },

  mounted () {
    this.get(null)
  },

  data () {
    return {
      loaded: false,
      results: {},
      page: 1,
    }
  }
}
</script>
