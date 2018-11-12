<template lang="pug">
transition(name="animodal")
  .modal.is-active
    .modal-background(@click="cancel")
    .modal-content
      .box
        .columns.is-multiline(v-if="is_many")
          .column.is-half(v-if="is_many",v-for="video in videos")
            VideoCardCover(:video="video")
        VideoCardCover(v-else,:video="videos[0]",type="boost")
        br
        ButtonBoost(:videos="videos")
    button.modal-close.is-large(autofocus,@click="cancel")
</template>

<script>
import ButtonBoost from '@/components/buttons/ButtonBoost'
import VideoCard from '@/components/video/VideoCard'
import VideoCardCover from '@/components/video/VideoCardCover'
export default {
  components: { VideoCard, ButtonBoost, VideoCardCover },
  props: {
    videos: {
      type: Array,
      required: true,
    }
  },
  methods: {
    cancel () {
      this.$emit('cancel')
    },
  },

  computed: {
    is_many () {
      return this.videos.length > 1
    },
  },
}
</script>
