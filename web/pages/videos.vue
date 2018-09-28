<template lang="pug">
#Videos.page
  section.section
    .container
      .level
        .level-left
          .level-item.ani-slide-in-right
            strong(v-if="videos.paginate"): FormatNumber(:value="videos.paginate.total")
            strong.has-text-grey(v-else) ..
            | &nbsp;videos
        .level-right
          .level-item.ani-slide-in-left
            VideoAdd(@refresh="refresh")

      .columns.is-multiline(v-if="!loaded")
        .column.is-one-third(v-for="n in videostat")
          VideoCardLoading

      .box.container-480(v-if="videostat < 1 && !loaded")
        .content
          .has-text-centered
            LoadingSpinner
      VideoNone(v-if="loaded && videos.data.length == 0")
      VideoList(v-else,:videos="videos.data",type="video",@refresh="refresh")
      Paginate(v-if="loaded",:paginate="videos.paginate",:query="query")
  BoostModal(v-if="boost",:video="boost")
</template>

<script>
import LoadingSpinner from '@/components/loading/LoadingSpinner'
import VideoCardLoading from '@/components/video/VideoCardLoading'

import VideoAdd from '@/components/video/VideoAdd'
import VideoList from '@/components/video/VideoList'
import VideoNone from '@/components/video/VideoNone'
import FormatNumber from '@/components/format/FormatNumber'
import Paginate from '@/components/buttons/Paginate'

import BoostModal from '@/components/modals/BoostModal'

export default {
  middleware: [ 'is-auth' ],
  components: {
    BoostModal,
    VideoAdd,
    VideoList,
    VideoNone,
    FormatNumber,
    Paginate,
    VideoCardLoading,
    LoadingSpinner
  },
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
  mounted () {
    this.get(this.$route.query)
  },
  computed: {
    videostat() {
      return this.$store.state.user.stats.videos < 10 ? this.$store.state.user.stats.videos : 9
    }
  },

  data () {
    return {
      videos: {},
      loaded: false,
      boost: false,
    }
  }
}
</script>
