<template lang="pug">
.card(:class="{'ani-zoom-out': deleted}")
  .card-image
    figure.image.is-16by9.yt-bg(:style="`background-image: url(${video.cover});`")
    .card-image-title.has-text-white
      span {{ video.title }}
      .card-image-actions
        .buttons.is-centered
          a.button.is-outlined.is-warning(target="_new",:href="`https://www.youtube.com/watch/?v=${video.id}`")
            span.icon
              i.mdi.mdi-youtube
            span Watch Video

  .card-content
    .field.is-grouped.is-grouped-multiline
      .control(v-if="video.views")
        .tags.has-addons
          span.tag.is-dark Views
          span.tag.is-info: FormatNumber(:value="video.views")
      .control(v-if="video.created_at")
        .tags.has-addons
          span.tag.is-dark Added
          span.tag.is-info: FormatDate(:value="video.created_at")

    .buttons.is-centered(v-if="channel",@click="add")
      button.button.is-primary(:disabled="video.added || adding",:class="{'is-loading': adding}")
        span.icon
          i.mdi.mdi-video-plus
        span(v-if="!video.added") Add Video
        span(v-else) Video added
  footer.card-footer
    a.card-footer-item.has-text-info
      span.icon
        i.mdi.mdi-history
      span Details
    a.card-footer-item.has-text-primary
      span.icon
        i.mdi.mdi-rocket
      span Boost 
    a.card-footer-item.has-text-danger(@click="confirm",:class="{'is-loading': deleting}")
      span.icon
        i.mdi.mdi-delete
      span Delete 
</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
import FormatDate from '@/components/format/FormatDate'
export default {
  components: { FormatNumber, FormatDate },
  props: {
    channel: {
      type: Boolean,
      required: true,
    },
    video: {
      type: Object,
      required: true,
    }
  },
  methods: {
    add () {
      this.adding = true
      this.$axios.post('video', {ids: [this.video.id]})
      .then( (response) => {
        if (response.data && response.data.data.success) {
          this.$toast.show(response.data.data)
          this.video.added = true
          this.adding = false
        }
      })
    },
    confirm () {
      this.deleting = true
      this.$modal.show({
        title: 'Video Removal',
        body: `Are you sure you want to delete <strong>${this.video.title}</strong>`,
        buttons: [{name: 'OK', class: 'is-primary', action: () => this.remove()}, {name: 'Cancel'}],
      })

    },
    remove () {
      this.$axios.delete(`/video/${this.video.id}`)
      .then( (response) => {
        if (response.data && response.data.data.success) {
          this.$toast.show(response.data.data)
          this.deleting = false
          this.deleted = true
          setTimeout( () => this.$emit('removed', this.video.id), 1000)
        }
      })
    },
  },

  data () {
    return {
      adding: false,
      deleting: false,
      deleted: false,
    }
  },
}
</script>
