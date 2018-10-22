<template lang="pug">
nav.navbar.is-fixed-top.is-dark(
  v-on-clickaway="away"
  :class="{'has-nav-shadow': shadow}")
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
      .navbar-start
        NavBarItem(
          route="packages",
          icon="package-variant",
          label="Packages",
          :close="close")

        NavBarItem(
          route="about",
          icon="cogs",
          label="How it works",
          :close="close")

      .navbar-end

        NavBarItem(
          v-if="auth && !connecting",
          route="dashboard",
          icon="view-dashboard",
          label="Dashboard",
          :close="close")

        NavBarItem(
          v-if="auth && !connecting",
          route="videos",
          icon="youtube",
          label="Videos",
          :close="close")

        NavBarItem(
          v-if="auth && !connecting",
          route="boosts",
          icon="rocket",
          label="Boosts",
          :close="close")

        transition(name="login",mode="out-in")
          NavBarUser(v-if="auth",:close="close",ref="user")
          NavBarLogin(v-else,:active="active",@prompt="prompt")

</template>

<script>
import { mapGetters } from 'vuex'
import { mixin as clickaway } from 'vue-clickaway'
import NavBarLogin from '@/components/layout/navbar/NavBarLogin'
import NavBarUser from '@/components/layout/navbar/NavBarUser'
import NavBarItem from '@/components/layout/navbar/NavBarItem'
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
    NavBarItem,
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

    scroll (event) {
      if (window.scrollY >= 100 && this.shadow == false) {
        this.shadow = true
      }
      if (window.scrollY <= 60 && this.shadow == true) {
        this.shadow = false
      }
    },

  },

  created () {
    if (process.browser) {
      window.addEventListener('scroll', this.scroll)
      this.scroll()
   }
  },

  destroyed () {
    if (process.browser) {
      window.removeEventListener('scroll', this.scroll)
    }
  },

  data () {
    return {
      shadow: false,
      active: false,
      connecting: false,
    }
  },
}
</script>
