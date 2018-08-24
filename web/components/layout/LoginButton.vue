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
      span Login
    | &nbsp;
</template>

<script>
export default {
  methods:  {

    attempt () {

      if (!this.prompt) {
        this.prompt = true
        setTimeout( () => this.$refs.email.focus() , 200)
        return true
      }

      if (this.email === '') {
        this.prompt = false
        this.errors = []
        return true
      }

      this.loading = true
      this.get(this.email)
      this.modal()

    },
    async get (email) {
      await this.$axios.get('/attempt', {params: {email: this.email}})
    },
    modal () {
      this.$modal.show({
        title: 'Check your Inbox',
        body: `
          <p>An e-mail was sent to your account with a link to login.</p>
          <hr />
          <p>You may <b>close</b> this window/tab now since clicking the link will open a new one!</p>
          `,
        buttons: [
          {name: 'Close'}, 
        ],
      })
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

