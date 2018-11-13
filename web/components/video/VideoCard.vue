<template lang="pug">
.card
  VideoCardCover(:video="video")

  .checkbox.is-pulled-right(v-if="selected",style="margin-top: 25px;")
    input.is-checkradio.is-checkbox.is-primary(
    :class="{'has-background-color': checked}",
    :id="`Video-${video.id}`"
    v-model="checked"
    type="checkbox")
    label(:for="`Video-${video.id}`")

  .card-content
    .field.is-grouped.is-grouped-multiline(v-if="is_video")
      .control
        .tags.has-addons
          span.tag.is-dark Added
          span.tag.is-info: FormatDate(:value="video.created_at")
      .control
        .tags.has-addons
          span.tag.is-dark Views
          span.tag.is-info: FormatNumber(:value="video.views")
      .control
        .tags.has-addons
          span.tag.is-dark Boosts
          span.tag.is-info: FormatNumber(:value="video.boosts_count")

    .buttons.is-centered(v-if="is_channel",@click="add")
      button.button.is-primary(
        :disabled="video.added || adding",
        :class="{'is-loading': adding, 'is-success': video.added}")
        span.icon
          i.mdi.mdi-video-plus
        span(v-if="!video.added") Add Video
        span(v-else) Video added
  footer.card-footer(v-if="is_video && buttons")
    nuxt-link.card-footer-item.has-text-info(:to="`/video/${video.id}`")
      span.icon
        i.mdi.mdi-history
      span Details
    a.card-footer-item.has-text-primary(@click="boost")
      span.icon
        i.mdi.mdi-rocket
      span Boost
    a.card-footer-item.has-text-danger(@click="confirm",:class="{'is-loading': deleting}")
      span.icon
        i.mdi.mdi-delete
      span Delete
</template>

<script>
import VideoCardCover from '@/components/video/VideoCardCover'
import FormatNumber from '@/components/format/FormatNumber'
import FormatDate from '@/components/format/FormatDate'
export default {
  components: { VideoCardCover, FormatNumber, FormatDate },
  props: {
    type: {
      type: String,
      required: true,
    },
    video: {
      type: Object,
      required: true,
    },
    selected: {
      type: Array,
      required: false,
    },
    buttons: {
      type: Boolean,
      required: false,
      default: true,
    }
  },

  computed: {
    is_channel () { return this.type === 'channel' },
    is_video () { return this.type === 'video' },
  },
  filters: {
    numeral (value) {
      if (process.browser)
        return window.numeral(value).format('0,0')
      return value
    },
  },

  watch: {
    'checked' () {
      this.check()
    },
    'selected' () {
      if (this.selected.includes(this.video.id) === false) {
        this.checked = false
      }
    },
  },

  methods: {

    check () {
      if (this.checked) {
        this.selected.push(this.video.id)
      } else {
        this.selected.splice(this.selected.indexOf(this.video.id), 1)
      }
      this.$emit('input', this.selected)
    },

    boost () {
      this.$emit('boost', this.video)
    },
    add () {
      this.adding = true
      this.$axios.post('video', {ids: [this.video.id]})
      .then( (response) => {
        if (response.data && response.data.data.success) {
          this.$toast.show(response.data.data)
          this.$store.dispatch('refresh')
          this.video.added = true
          this.adding = false
        }
      }).catch(() => {}).then(() => this.adding = false)
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
      checked: false,
      adding: false,
      deleting: false,
      deleted: false,
    }
  },
}
</script>

