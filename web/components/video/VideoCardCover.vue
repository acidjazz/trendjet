<template lang="pug">
.card-image
  figure.image.is-16by9.yt-bg(:style="`background-image: url(${video.cover});`")
  .card-image-title.has-text-white
    span {{ video.title }}
    .card-image-actions
      .buttons.is-centered
        a.button.is-inverted.is-outlined.is-dark(target="_new",:href="`https://www.youtube.com/watch/?v=${video.id}`")
          span.icon
            i.mdi.mdi-youtube
          span Watch Video on YouTube
</template>

<script>
export default {
  props: {
    video: {
      type: Object,
      required: true,
    }
  }
}
</script>
