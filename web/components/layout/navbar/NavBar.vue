<template lang="pug">
nav.navbar.is-fixed-top.is-dark(v-on-clickaway="away")
  .container
    .navbar-brand
      nuxt-link.navbar-item(to="/")
        .logo
          include ../../../static/logo.svg
        .title.is-hidden-mobile.has-text-white trendjet
      a.navbar-burger(@click="swap",:class="{'is-active': active}")
        span
        span
        span
    .navbar-menu(:class="{'is-active': active}")
      .navbar-end
        nuxt-link.navbar-item(
          @click.native="close",
          v-if="!connecting || mobile",
          to="/",
          :class="{'is-active': $route.name == 'index'}")
          span.icon
            i.mdi.mdi-view-dashboard
          span Dashboard
        nuxt-link.navbar-item(
          @click.native="close",
          v-if="!connecting || mobile",
          to="/packages",
          :class="{'is-active': $route.name == 'plans'}")
          span.icon
            i.mdi.mdi-package-variant
          span Packages
        transition(name="login",mode="out-in")
          NavBarUser(v-if="auth",:close="close",ref="user")
          NavBarLogin(v-else,:active="active",@prompt="prompt")
</template>

<script>
import { mapGetters } from 'vuex'
import { mixin as clickaway } from 'vue-clickaway'
import NavBarLogin from '@/components/layout/navbar/NavBarLogin'
import NavBarUser from '@/components/layout/navbar/NavBarUser'
export default {
  mixins: [ clickaway ],
  computed: {
    ...mapGetters(['auth']),
    mobile () {
      return window && window.innerWidth <= 1080
    },
  },
  components: {
    NavBarLogin,
    NavBarUser,
  },
  methods: {
    open () { this.active = false },
    close () { this.active = false },
    swap () { this.active = !this.active },
    reflect () { this.$refs.user.off() },
    away () {
      this.close();
      if (this.$refs.user) {
        this.reflect();
      }
    },
    prompt (toggle) {
      this.connecting = toggle
    },
  },
  data () {
    return {
      active: false,
      connecting: false,
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

.login-enter-active
  transition all .3s ease 0s
  overflow hidden
.login-leave-active
  transition all .3s ease 0s
  overflow hidden

.login-enter
  transform translate(0px, -10px)
.login-leave-to
  transform translate(0px, -10px)
.login-enter, .login-leave-to
  opacity 0


</style>
