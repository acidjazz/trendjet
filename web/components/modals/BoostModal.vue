<template lang="pug">
transition(name="animodal")
  .modal.is-active
    .modal-background(@click="cancel")
    .modal-content
      VideoCard(:video="video",type="boost")
    button.modal-close.is-large(autofocus,@click="cancel")
</template>

<script>
import VideoCard from '@/components/video/VideoCard'
export default {
  components: { VideoCard },
  props: {
    video: {
      type: Object,
      required: true,
    }
  },
  methods: {
    cancel () {
      this.$emit('cancel')
    },
  },
}
</script>
