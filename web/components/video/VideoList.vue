<template lang="pug">
.columns.is-multiline
  .column.is-one-third(
    v-for="video in videos"
    :class="{'ani-zoom-out': deleted === video.id}")
    VideoCard(
      :video="video"
      :type="type"
      @removed="removed"
      @boost="boost")
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
    type: {
      type: String,
      required: true,
    },
    refresh: {
      type: Function,
      required: false,
    },
  },
  methods: {
    removed (id) {
      this.deleted = id
      this.$store.dispatch('refresh')
      // this.$emit('refresh')
    },
    boost (video) {
      this.$emit('boost', video)
    },
  },

  data () {
    return {
      deleted: false,
    }
  }
}
</script>

