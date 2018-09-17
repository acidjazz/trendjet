<template lang="pug">
.columns.is-multiline
  .column(v-for="video in videos").is-one-third
    VideoCard(:video="video",:channel="channel",@removed="removed")
</template>

<script>
import VideoCardLoading from '@/components/video/VideoCardLoading'
import VideoCard from '@/components/video/VideoCard'
export default {
  components: { VideoCard, VideoCardLoading },
  props: {
    videos: {
      type: [Object,Array],
    },
    channel: {
      type: Boolean,
      required: true,
    },
  },
  methods: {
    removed (id) {
      for (let index in this.videos) {
        if (this.videos[index].id === id) {
          this.$delete(this.videos, index)
          console.log('deleted', this.videos[index])
        }
      }
    }
  },
}
</script>

