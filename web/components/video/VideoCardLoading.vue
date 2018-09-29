<template lang="pug">
.card
  .card-image.is-gradient-loading
    figure.image.is-16by9
  .card-content
    .field.is-grouped.is-grouped-multiline(v-if="is_video")
      .control
        .tags.has-addons
          span.tag.is-dark Views
          span.tag.is-info.is-gradient-loading &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      .control
        .tags.has-addons
          span.tag.is-dark Added
          span.tag.is-info.is-gradient-loading &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    .buttons.is-centered(v-if="is_channel")
      button.button.is-primary(disabled)
        span.icon
          i.mdi.mdi-video-plus
        span Add Video

  footer.card-footer(v-if="is_video")
    .card-footer-item.has-text-grey
      span.icon
        i.mdi.mdi-history
      span Details
    .card-footer-item.has-text-grey
      span.icon
        i.mdi.mdi-rocket
      span Boost
    .card-footer-item.has-text-grey
      span.icon
        i.mdi.mdi-delete
      span Delete

</template>

<script>
export default {
  props: {
    type: {
      type: String,
      required: true,
    },
  },
  computed: {
    is_channel () { return this.type === 'channel' },
    is_video () { return this.type === 'video' },
  },
}
</script>
