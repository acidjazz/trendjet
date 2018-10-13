<template lang="pug">
nav.navbar.navbar-second.is-link
  .container
    .navbar-brand
      nuxt-link.navbar-item(to="/admin",:class="{'is-active': page === 'admin'}")
        span.icon
          i.mdi.mdi-security
        span admin
      a.navbar-burger(@click="menu = !menu")
        span
        span
        span
    .navbar-menu(:class="{'is-active': menu}")
      .navbar-start
        nuxt-link.navbar-item(to="/admin/users",:class="{'is-active': page === 'users'}")
          span.icon
            i.mdi.mdi-account-multiple
          span Users
</template>

<script>
export default {

  computed: {
    page () {
      return this.$route.name.replace('admin-', '')
    },
  },

  data () {
    return {
      menu: false,
    }
  },
}
</script>
