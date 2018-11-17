<template lang="pug">
#Videos.page
  BreadCrumbs(:crumbs="crumbs")
  section.section
    .container
      .level
        .level-left
          .level-item.ani-slide-in-right(v-if="$store.state.user.stats")
            strong: FormatNumber(:value="$store.state.user.stats.videos")
            | &nbsp;videos
          .level-item.ani-slide-in-right
            BoostMultiple(:all="selectAll",:selected="selected",:clear="clear",:boost="boost")
        .level-right
          .level-item.ani-slide-in-left
            VideoAdd(@refresh="refresh")

      .columns.is-multiline(v-if="!loaded")
        .column.is-one-third(v-for="n in videostat")
          VideoCardLoading(type="video")

      .box.container-480(v-if="videostat < 1 && !loaded")
        .content
          .has-text-centered
            LoadingSpinner
      VideoNone(v-if="loaded && videos.data.length == 0")
      VideoList(v-else
      :videos="videos.data"
      :selected="selected"
      type="video"
      @refresh="refresh"
      @boost="boost")
      Paginate(v-if="loaded",:paginate="videos.paginate",:query="query")
  BoostModal(v-if="boosting",:videos="boosting",@cancel="cancel")
</template>

<script>

import BreadCrumbs from '@/components/layout/BreadCrumbs'

import LoadingSpinner from '@/components/loading/LoadingSpinner'
import VideoCardLoading from '@/components/video/VideoCardLoading'
import VideoAdd from '@/components/video/VideoAdd'
import VideoList from '@/components/video/VideoList'
import VideoNone from '@/components/video/VideoNone'
import FormatNumber from '@/components/format/FormatNumber'
import BoostMultiple from '@/components/buttons/BoostMultiple'
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
    BoostMultiple,
    VideoCardLoading,
    LoadingSpinner,
    BreadCrumbs,
  },
  methods: {

    selectAll () {
      for (let video of this.videos.data) {
        this.selected.push(video.id)
      }
    },
    clear () {
      this.selected = []
    },
    refresh () {
      this.get(this.$route.query)
    },
    async get (query) {
      this.videos = (await this.$axios.get('/video', {params: query})).data
      this.loaded = true
    },
    query (params) {
      let query = Object.assign({}, this.$route.query, params)
      this.$router.push({ query: query })
      this.get(query)
    },
    boost (video) {
      if (Array.isArray(video)) {
        let videos = []
        for (let vid of this.videos.data) {
          if (video.includes(vid.id)) {
            videos.push(vid)
          }
        }
        this.boosting = videos
        return
      }
      this.boosting = [video]
    },
    cancel () {
      this.boosting = false
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
      boosting: false,
      selected: [],
      crumbs: [
        {
          name: 'My Videos',
          icon: 'youtube',
          to: '/videos',
          active: true,
        },
      ],
    }
  }
}
</script>
