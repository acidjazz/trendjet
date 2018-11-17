<template lang="pug">
.columns.is-multiline
  .column.is-one-third(
    v-for="video, index in videos"
    :key="index",
    :class="{'ani-zoom-out': deleted === video.id}")
    VideoCard(
      :video="video"
      :type="type"
      :change="change"
      :selected="is_selected(video.id)"
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
    selected: {
      type: Array,
      required: true,
    },
  },
  methods: {
    removed (id) {
      this.deleted = id
      setTimeout( () => this.$emit('refresh'), 1000)
    },
    boost (video) {
      this.$emit('boost', video)
    },
    change (value, id) {
      if (value && !this.is_selected(id)) {
        this.selected.push(id)
      }
      if (!value) {
        this.selected.splice(this.selected.indexOf(id), 1)
      }
    },
    is_selected (id) {
      return this.selected.indexOf(id) !== -1
    }
  },

  data () {
    return {
      deleted: false,
    }
  }
}
</script>

