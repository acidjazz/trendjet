<template lang="pug">
.columns.is-multiline
  .column(v-for="video in videos.data").is-one-third.ani-zoom-in
    .card
      .card-image
        figure.image.is-2by1.yt-bg(:style="`background-image: url(${video.cover});`")
        .card-image-title.has-text-white {{ video.title }}
      .card-content
        .field.is-grouped.is-grouped-multiline
          .control
            .tags.has-addons
              span.tag.is-dark Views
              span.tag.is-info: FormatNumber(:value="video.current")
          .control
            .tags.has-addons
              span.tag.is-dark Added
              span.tag.is-info: FormatDate(:value="video.created_at")
        .buttons.has-addons.is-centered
          button.button
            span.icon.has-text-primary
              i.mdi.mdi-rocket
            span Boost
          button.button
            span.icon
              i.mdi.mdi-history
            span History
          button.button
            span.icon.has-text-danger
              i.mdi.mdi-delete
            span Delete
</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
import FormatDate from '@/components/format/FormatDate'
export default {
  components: { FormatNumber, FormatDate },
  props: {
    videos: {
      type: Object,
    }
  }
}
</script>

