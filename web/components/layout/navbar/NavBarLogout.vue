<template lang="pug">
a.navbar-item(@click="logout")
  .level
    .level-left
  LoadingSpinner(size="24px",v-if="loading")
  span.icon(v-else)
    i.mdi.mdi-logout
  span &nbsp;Logout
</template>

<script>
import LoadingSpinner from '@/components/loading/LoadingSpinner'
export default {
  components: { LoadingSpinner },
  methods: {
    async logout () {
      this.loading = true
      await this.$axios.get('/logout')
      if (window && window.Cookies) {
        window.Cookies.remove('token')
        this.$router.push('/')
        setTimeout(() => {
          this.loading = false
          this.$store.commit('user', null)
          this.$message.show({
            type: 'success', 
            message: 'Logout Successful'
          })
        }, 200)
      }
    },
  },

  data () {
    return {
      loading: false,
    }
  },
}
</script>
