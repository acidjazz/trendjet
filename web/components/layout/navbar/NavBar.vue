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
      transition(name="slide-fade",mode="out-in")
        NavBarUser(v-if="auth")
        NavBarLogin(v-else)
</template>

<script>
import { mapGetters } from 'vuex'
import NavBarLogin from '@/components/layout/navbar/NavBarLogin'
import NavBarUser from '@/components/layout/navbar/NavBarUser'
export default {
  computed: { ...mapGetters(['auth'])},
  components: { 
    NavBarLogin,
    NavBarUser,
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

.slide-fade-enter-active
  transition all .3s ease .0s
.slide-fade-leave-active 
  transition all .3s ease .2s

.slide-fade-enter
  transform translate(0, -30px)
.slide-fade-leave-to
  transform translate(0, -30px)
.slide-fade-enter, .slide-fade-leave-to
  opacity 0


</style>
