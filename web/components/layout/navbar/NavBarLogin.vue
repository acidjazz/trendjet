<template lang="pug">
.field(:class="{'has-addons': prompt}")
  p.control(v-if="prompt").has-icons-left.has-icons-right
    input.input.ani-slide-in(
      type="text",
      placeholder="E-mail address",
      v-model="email",
      ref="email",
      :class="{'is-danger': errors}",
      @keyup.enter="attempt")
    span.icon.is-small.is-left
      i.mdi.mdi-email
    span.icon.is-small.is-right.has-text-danger(v-if="errors")
      i.mdi.mdi-alert
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
        this.errors = false
        return true
      }

      this.loading = true
      this.get(this.email)

    },
    get (email) {
      this.errors = false
      this.$axios.get('/attempt', {params: {email: this.email}})
        .then((response) => { 
          window.Cookies.set('attempt', response.data.data, { expires: 1})
          this.modal() 
        })
        .catch((error) => { this.errors = true })
        .then((result) => { this.loading = false })
    },
    modal () {
      this.$modal.show({
        title: 'Check your Inbox',
        body: `<p>An e-mail was sent to your account with a link to login.</p>
          <hr />
          <p>You may <b>close this window</b> now since clicking the link will open a new one!</p>`,
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
      errors: false,
    }
  }
}
</script>

