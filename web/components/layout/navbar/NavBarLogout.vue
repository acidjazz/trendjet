<template lang="pug">
a.navbar-item.button.is-text(@click="logout",:class="{'is-loading': loading}")
  span.icon
    i.mdi.mdi-logout
  span Logout
</template>

<script>
export default {
  methods: {
    async logout () {
      this.loading = true
      await this.$axios.get('/logout')
      if (window && window.Cookies) {
        window.Cookies.remove('token')
        this.$message.show({
          type: 'success', 
          message: 'Logout Successful'
        })
        this.$router.push('/')
        setTimeout(() => {
          this.loading = false
          this.$store.commit('user', null)
        }, 100)
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
