<template lang="pug">
.columns.is-multiline
  .column(v-for="video in videos").is-one-third.ani-zoom-in-half
    VideoCard(:video="video",:channel="channel")
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
  }
}
</script>

