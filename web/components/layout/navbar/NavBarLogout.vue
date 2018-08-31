<template lang="pug">
a.navbar-item(@click="logout")
  .level
    .level-left
  LoadingSpinner(size="24px",v-if="loading")
  span.icon.has-text-success(v-else-if="loggedout")
    i.mdi.mdi-check
  span.icon(v-else)
    i.mdi.mdi-logout
  span &nbsp;Logout
</template>

<script>
import LoadingSpinner from '@/components/loading/LoadingSpinner'
export default {
  props: {
    close: {
      type: Function,
      required: true,
    },
  },
  components: { LoadingSpinner },
  methods: {
    async logout () {
      this.loading = true
      await this.$axios.get('/logout')
      if (window && window.Cookies) {
        window.Cookies.remove('token')
        this.$router.push('/')
        this.loading = false
        this.loggedout = true
        setTimeout(() => this.close(), 200)
        setTimeout(() => this.$store.commit('user', null) , 400)
      }
    },
  },

  data () {
    return {
      loading: false,
      loggedout: false,
    }
  },
}
</script>
