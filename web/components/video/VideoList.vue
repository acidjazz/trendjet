<template lang="pug">
.columns.is-multiline
  .column(v-for="video in videos").is-one-third.ani-zoom-in
    VideoCard(:video="video",:channel="channel")
</template>

<script>
import VideoCard from '@/components/video/VideoCard'
export default {
  components: { VideoCard },
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

