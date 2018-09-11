<template lang="pug">
#AddVideo
  .field(:class="{'has-addons': prompt}")
    p.control.has-icons-right.has-icons-left.ani-slide-in-left(
      v-if="prompt",
      :class="{'is-closing': closing, 'is-loading': loading}")
      input.input(
        type="text",
        v-model="url",
        ref="input",
        @keyup.enter="attempt",
        placeholder="Channel or video link",
      )
      span.icon.is-left
        i.mdi.mdi-link
    p.control
      button.button.is-primary(@click="attempt",:disabled="loading")
        span.icon
          i.mdi.mdi-video-plus
        span(v-if="!prompt") Add Videos
  p.help.is-danger(v-if="error") {{ error }}

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

      if (this.url === '') {
        this.compact()
        this.error = false
        return true
      }

      this.loading = true

      this.$axios.get('/youtube', { params: {url: this.url}})
      .then( (response) => {
        console.log(response.data)
        if (response.data.data.type === 'unknown') {
          this.error = 'Unknown URL'
        }

      })
      .then( (response) => this.loading = false)

    },

  },
  data () {
    return {
      url: '',
      loading: false,
      closing: false,
      prompt: false,
      error: false,
    }
  }
}
</script>
