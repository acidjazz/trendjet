<template lang="pug">
#Videos.page
  section.section
    .container
      .level
        .level-left
          .level-item.ani-slide-in-right
            strong(v-if="videos.paginate"): FormatNumber(:value="videos.paginate.total")
            strong(v-else) 0
            | &nbsp;videos, 
            | page&nbsp;
            strong(v-if="videos.paginate") {{ videos.paginate.current_page }}
            strong(v-else) 1
        .level-right
          .level-item.ani-slide-in-left
            VideoAdd(@refresh="refresh")

      .columns.is-multiline(v-if="!loaded")
        .column.is-one-third(v-for="n in 6")
          VideoCardLoading

      VideoList(:videos="videos.data",:channel="false")
      Paginate(v-if="loaded",:paginate="videos.paginate",:query="query")
</template>

<script>
import VideoCardLoading from '@/components/video/VideoCardLoading'

import VideoAdd from '@/components/video/VideoAdd'
import VideoList from '@/components/video/VideoList'
import FormatNumber from '@/components/format/FormatNumber'
import Paginate from '@/components/buttons/Paginate'
export default {
  middleware: [ 'is-auth' ],
  components: { VideoAdd, VideoList, FormatNumber, Paginate, VideoCardLoading },
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
