<template lang="pug">
#Channel.page
  section.section
    .container
      .level
        .level-left
          .level-item.ani-slide-in-right(v-if="loaded")
            strong: FormatNumber(:value="results.totalResults")
            span(v-if="results.totalResults == 1") &nbsp;video for&nbsp;
            span(v-else) &nbsp;videos for&nbsp;
            strong {{ results.channelTitle }}
        .level-right
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

      VideoList(v-if="loaded",:videos="results.videos",:selected="selected",type="channel")
      .columns.is-multiline(v-if="!loaded")
        .column.is-one-third(v-for="n in 9")
          VideoCardLoading(type="channel")
</template>

<script>
import VideoList from '@/components/video/VideoList'
import VideoCardLoading from '@/components/video/VideoCardLoading'
import FormatNumber from '@/components/format/FormatNumber'
export default {
  middleware: [ 'is-auth' ],
  components: { VideoList, FormatNumber, VideoCardLoading },
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
      this.results = (await this.$axios.get(`/youtube/channel/${this.$route.params.id}`,
        {params: {pageToken: pageToken}})).data.data
      this.loaded = true
    },
  },

  mounted () {
    this.get(null)
  },

  data () {
    return {
      selected: [],
      loaded: false,
      results: {},
      page: 1,
    }
  }
}
</script>
