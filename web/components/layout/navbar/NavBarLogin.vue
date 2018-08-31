<template lang="pug">
.navbar-end
  .navbar-item
    span.field.display-inline-block(:class="{'has-addons': prompt}")
      p.control.ani-slide-in.delay-2.has-icons-left.has-icons-right(
        :class="{'is-loading': loading.email}",
        v-if="prompt")
        input.input(
          type="text",
          placeholder="E-mail address",
          v-model="email",
          ref="email",
          :class="{'is-danger': errors}",
          @keyup.enter="attempt")
        span.icon.is-small.is-left.ani-slide-in.delay-3
          i.mdi.mdi-email
        span.icon.is-small.is-right.has-text-danger(v-if="errors")
          i.mdi.mdi-alert
      p.control(v-if="prompt")
        button.button.ani-slide-in.delay-1(
          :class="{'is-loading': loading.facebook}",
          @click="oauth('facebook')")
          span.icon: i.mdi.mdi-facebook
      p.control(v-if="prompt")
        button.button.ani-slide-in(
          :class="{'is-loading': loading.google}",
          @click="oauth('google')")
          span.icon: i.mdi.mdi-google
      p.control
        button.button(
          @click="attempt",
          :class="{'is-dark': !prompt}",
          ref="loginButton")
          span.icon
            i.mdi.mdi-login-variant
          span Log in
        | &nbsp;
</template>

<script>
export default {
  methods:  {

    oauth(provider) {

      this.loading[provider] = true
      console.log(this.loading)

      let width = 640
      let height = 660
      let left = window.screen.width / 2 - (width / 2)
      let top = window.screen.height / 2 - (height / 2)

      let win = window.open(process.env.API_URL + 'redirect/' + provider, 'Log in',
        `toolbar=no, location=no, directories=no, status=no, menubar=no, scollbars=no, 
        resizable=no, copyhistory=no, width=${width},height=${height},top=${top},left=${left}`
      )

      let interval = setInterval(() => {
        try {
          if (win === null || win.closed) {
            clearInterval(interval)
            this.loading[provider] = false
          }
        } catch (e) {
        }
      }, 200)

    },

    handleMessage (event) {
      console.log('handleMessage', event.data.user)
      if (event.data.user && event.data.token) {
        this.oauthComplete(event.data)
      }
    },

    oauthComplete (result) {
      if (process.SERVER) return true
      window.localStorage.setItem('trendjet', JSON.stringify(result))
      this.$store.commit('user', result.user)
      this.$message.show({ type: 'success', message: 'Login Successful' })
    },

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

      this.loading.email = true
      this.get(this.email)

    },
    get (email) {
      this.errors = false
      this.$axios.get('/attempt', {params: {email: this.email}})
        .then((response) => { 
          window.Cookies.set('attempt', response.data.data.cookie, { expires: 1})
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

  created () {
    if (process.browser) window.addEventListener('message', this.handleMessage)
  },
  destroyed () {
    if (process.browser) window.removeEventListener('message', this.handleMessage)
  },

  data () {
    return {
      loading: {
        email: false,
        facebook: false,
        google: false,
      },
      prompt: false,
      email: '',
      errors: false,
    }
  }
}
</script>
