<template lang="pug">
#Videos.page
  section.section
    .container
      .level
        .level-left(v-if="loaded")
          .level-item.ani-slide-in-right
            strong: FormatNumber(:value="videos.paginate.total")
            | &nbsp;videos
        .level-right(v-if="loaded")
          .level-item.ani-slide-in-left
            VideoAdd(@refresh="refresh")
      VideoList(:videos="videos.data",:channel="false")
      Paginate(v-if="loaded",:paginate="videos.paginate",:query="query")
</template>

<script>
import VideoAdd from '@/components/video/VideoAdd'
import VideoList from '@/components/video/VideoList'
import FormatNumber from '@/components/format/FormatNumber'
import Paginate from '@/components/buttons/Paginate'
export default {
  components: { VideoAdd, VideoList, FormatNumber, Paginate },
  methods: {
    refresh () { this.get() },
    async get (query) { 
      this.loaded = false
      this.videos = {}
      this.videos = (await this.$axios.get('/video', {params: query})).data 
      this.loaded = true
    },
    query (params) {
      let query = Object.assign({}, this.$route.query, params)
      this.$router.push({ query: query })
      this.get(query)
    },
  },
  mounted () { this.get(this.$route.query) },
  data () {
    return {
      videos: {},
      loaded: false,
    }
  }
}
</script>
