<template lang="pug">
.button.is-dark(@click="logout")
  span.icon
    i.mdi.mdi-logout
  span Logout
</template>

<script>
export default {
  methods: {
    logout () {
      this.$axios.get('/logout')
      if (window && window.Cookies) {
        window.Cookies.set('token', null)
        document.location = '/'
      }
    },
  },
}
</script>
