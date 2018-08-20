<template lang="pug">
nav.navbar.is-dark.is-fixed-top
  .container
    .navbar-brand
      .navbar-item 
        .logo
          include ../../static/logo.svg
        .title.has-text-primary.is-hidden-mobile trendjet
    .navbar-menu
      .navbar-end
        .navbar-item
          LoginButton
</template>

<script>
import LoginButton from '@/components/layout/LoginButton'
export default {
  components: { 
    LoginButton,
  },
  data () {
    return {
      burger: false,
    }
  },
}
</script>

<style lang="stylus">
.navbar-brand .navbar-item .logo
  width 30px
  height 30px
  margin-right 10px
</style>
