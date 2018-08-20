<template lang="pug">
.field(:class="{'has-addons': prompt}")
  p.control(v-if="prompt").has-icons-left
    input.input.input-slide-in(
      type="text",
      placeholder="E-mail address",
      v-model="email",
      ref="email",
      @keyup.enter="attempt")
    span.icon.is-small.is-left
      i.mdi.mdi-email
  p.control
    button.button(
      @click="attempt",
      :class="{'is-loading': loading, 'is-dark': !prompt}",
      ref="loginButton")
      span.icon
        i.mdi.mdi-login-variant
      span Connect

</template>

<script>
export default {
  methods:  {
    attempt () {
      if (!this.prompt) {
        this.prompt = true
        setTimeout( () => {
          if (this.$refs.email) {
            this.$refs.email.focus()
          }
        }, 200)
        return true
      }
      if (this.email === '') {
        this.prompt = false
        this.errors = []
        return true
      }
    },
  },
  data () {
    return {
      loading: false,
      prompt: false,
      email: '',
      errors: [],
    }
  }
}
</script>

