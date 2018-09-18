<template lang="pug">
#AddVideo
  .field(:class="{'has-addons': prompt}")
    p.control.has-icons-right.has-icons-left.ani-slide-in-left(
      v-if="prompt",
      :class="{'is-closing': closing, 'is-loading': loading}")
      input.input(
        type="text",
        v-model="string",
        ref="input",
        @keyup.enter="attempt",
        placeholder="Channel or video link or id",
      )
      span.icon.is-left
        i.mdi.mdi-link
    p.control
      button.button.is-primary(@click="attempt",:disabled="loading")
        span.icon
          i.mdi.mdi-video-plus
        span(v-if="!prompt") Add Videos
  p.help.is-danger(v-if="errors",v-for="error in errors") {{ error[0] }}

</template>

<script>
export default {
  methods: {

    compact () {
      this.closing = true
      setTimeout(() => {
        this.closing = false
        this.prompt = false
      }, 200)

    },

    attempt () {

      if (!this.prompt) {
        this.prompt = true
        setTimeout(() => this.$refs.input.focus(), 200)
        return true
      }

      if (this.string === '') {
        this.compact()
        this.error = false
        return true
      }

      this.loading = true

      this.$axios.get('/youtube/parse', { params: {string: this.string}})
      .then( (response) => {

        if (response.data.data.type === 'video') {
          this.add(response.data.data.id)
        }
        if (response.data.data.type === 'channel') {
          this.$router.push(`/channel/${response.data.data.id}`)
        }

      }).catch( (error) => {
        this.errors = error.response.data.errors
      })
      .then( (response) => this.loading = false)

    },

    add (id) {
      this.$axios.post('/video', {ids: [id]})
      .then( (response) => {
        if (response.data && response.data.data.success) {
          this.$toast.show(response.data.data)
          this.string = ''
          this.compact()
          this.$emit('refresh')
        }
      })
    },
  },
  data () {
    return {
      string: '',
      loading: false,
      closing: false,
      prompt: false,
      errors: [],
    }
  }
}
</script>
