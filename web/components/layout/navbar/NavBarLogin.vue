<template lang="pug">
.navbar-end
  .navbar-item
    span.field.display-inline-block(:class="{'has-addons': prompt}")
      p.control.ani-slide-in-left.delay-2.has-icons-left.has-icons-right(
        :class="{'is-loading': loading.email,'is-closing': closing}",
        v-if="prompt")
        input.input(
          type="text",
          placeholder="E-mail address",
          v-model="email",
          :class="{'is-danger': errors}",
          @keyup.enter="attempt")
        span.icon.is-small.is-left.ani-slide-in-left.delay-3(:class="{'is-closing': closing}")
          i.mdi.mdi-email
        span.icon.is-small.is-right.has-text-danger(v-if="errors")
          i.mdi.mdi-alert
        span.icon.is-small.is-right.has-text-success(v-if="success.email")
          i.mdi.mdi-check
      p.control(v-if="prompt")
        button.button.ani-slide-in-left.delay-1(
          :class="{'is-loading': loading.facebook,'is-closing': closing}",
          @click="oauth('facebook')")
          span.icon.has-text-success(v-if="success.facebook"): i.mdi.mdi-check
          span.icon(v-else): i.mdi.mdi-facebook
      p.control(v-if="prompt")
        button.button.ani-slide-in-left(
          :class="{'is-loading': loading.google,'is-closing': closing}",
          @click="oauth('google')")
          span.icon.has-text-success(v-if="success.google"): i.mdi.mdi-check
          span.icon(v-else): i.mdi.mdi-google
      p.control
        button.button(:class="{'is-dark': !prompt || closing}",@click="attempt")
          span.icon.ani-zoom-in
            i.mdi.mdi-login-variant
          span Connect
        | &nbsp;
</template>

<script>
export default {
  methods:  {

    oauth(provider) {

      this.loading[provider] = true

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
      if (event.data.user && event.data.token) {
        this.oauthComplete(event.data)
      }
    },

    oauthComplete (result) {
      if (process.SERVER) return true
      this.loading[result.provider] = false
      this.success[result.provider] = true
      window.localStorage.setItem('trendjet', JSON.stringify(result))
      setTimeout( () => this.close(), 300)
      setTimeout( () => this.$store.commit('user', result.user), 340)
    },

    attempt () {

      if (!this.prompt) {
        this.prompt = true
        return true
      }

      if (this.email === '') {
        this.close()
        this.errors = false
        return true
      }

      this.loading.email = true
      this.get(this.email)

    },

    close () {
      this.closing = true
      setTimeout(() => {
        this.closing = false
        this.prompt = false
      }, 200)
    },

    get (email) {
      this.errors = false
      this.$axios.get('/attempt', {params: {email: this.email}})
        .then((response) => { 
          this.success.email = true
          window.Cookies.set('attempt', response.data.data.cookie, { expires: 1})
          this.modal() 
        })
        .catch((error) => { this.errors = true })
        .then((result) => { this.loading.email = false })
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
      closing: false,
      loading: {
        email: false,
        facebook: false,
        google: false,
      },
      success: {
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

