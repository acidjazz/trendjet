<template lang="pug">
transition(name="animodal")
  .modal.is-active
    .modal-background
    .modal-content
      VideoCard(:video="video",type="boost")
    button.modal-close.is-large(autofocus)
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
}
</script>
