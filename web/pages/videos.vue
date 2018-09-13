<template lang="pug">
#Videos.page
  section.section
    .container
      .level
        .level-left
          .level-item
            b(v-if="videos.paginate"): FormatNumber(:value="videos.paginate.total")
            | &nbsp;Videos
        .level-right
          .level-item
            VideoAdd(@refresh="refresh")
      VideoList(:videos="videos")
</template>

<script>
import VideoAdd from '@/components/video/VideoAdd'
import VideoList from '@/components/video/VideoList'
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { VideoAdd, VideoList, FormatNumber },
  methods: {
    refresh () { this.get() },
    async get () { this.videos = (await this.$axios.get('/video')).data },
  },
  mounted () { this.get() },
  data () {
    return {
      videos: {},
    }
  }
}
</script>
