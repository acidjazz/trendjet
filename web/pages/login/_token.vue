<template lang="pug">
#Login.page
  section.section
    .container.container-320
      .content
        .box.is-link.has-background-success-light
          .media.is-vertical-center
            .media-left
              LoadingSpinner(v-if="loading",size="46px")
              span.icon.is-large.has-text-danger(v-if="error")
                i.mdi.mdi-48px.mdi-alert-circle
              span.icon.is-large.has-text-success(v-if="success")
                i.mdi.mdi-48px.mdi-check-circle
            .media-content
              strong(v-if="loading") &nbsp;Verifying authentication..
              strong(v-if="error") &nbsp;Authentication error
              strong(v-if="success") &nbsp;Verified, Logging in..
</template>

<script>
import LoadingSpinner from '@/components/loading/LoadingSpinner'
export default {
  components: { LoadingSpinner },

  methods: {

    login () {

      let params = {
        token: this.$route.params.token,
        cookie: window.Cookies.get('attempt') || this.hash,
      }

      this.$axios.get('/login', {params: params})
        .then( (response) => {
          this.$store.commit('user', response.data.data.user)
          this.state = 'success'
          window.Cookies.remove('attempt')
          setTimeout( () => this.$router.push('/'), 2000)
        })
        .catch( (error) => {
          this.state = 'error'
        })
        .then( () => this.checked = true)

    }
  },
  mounted () {
    if (!this.checked) this.login()
    this.API_URL = process.env.API_URL
  },

  computed: {
    loading () { return this.state === 'loading' },
    error () { return this.state === 'error' },
    success () { return this.state ===  'success' },
  },

  data () {
    return {
      checked: false,
      API_URL: false,
      state: 'loading',
      hash: '85f8c6470a3633d4481ac9be547efe5a0c5163c3ad10307ad8215e0c9dca074c',
    }
  }
}
</script>
