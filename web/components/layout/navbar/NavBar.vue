<template lang="pug">
nav.navbar.is-dark.is-fixed-top
  .container
    .navbar-brand
      nuxt-link.navbar-item(to="/")
        .logo
          include ../../../static/logo.svg
        .title.has-text-primary.is-hidden-mobile trendjet
      a.navbar-burger(@click="burger = !burger",:class="{'is-active': burger}")
        span
        span
        span
    .navbar-menu(:class="{'is-active': burger}")
      .navbar-end
        .navbar-item
          NavBarLogin(v-if="!auth")
          NavBarLogout(v-else)
</template>

<script>
import { mapGetters } from 'vuex'
import NavBarLogin from '@/components/layout/navbar/NavBarLogin'
import NavBarLogout from '@/components/layout/navbar/NavBarLogout'
export default {
  computed: { ...mapGetters(['auth'])},
  components: { 
    NavBarLogin,
    NavBarLogout,
  },
  data () {
    return {
      burger: false,
    }
  },
}
</script>

<style lang="stylus">
@import '../../../assets/stylus/includes/*'
.navbar-brand .navbar-item .logo
  width 30px
  height 30px
  margin-right 10px
.navbar-brand  a.navbar-burger:hover
  color picton
</style>
