<template lang="pug">
.navbar-end
  .navbar-item.has-dropdown.is-hoverable
    .navbar-link
      .level
        .level-left
          .level-item
            figure.image.is-32x32
              img.avatar.object-cover.ani-zoom-in(:src="$store.state.user.avatar")
          .level-item
            span {{ $store.state.user.name }}
    .navbar-dropdown.is-right
      nuxt-link.navbar-item(v-if="isAdmin",to="/admin",@click.native="close")
        span.icon
          i.mdi.mdi-security
        span Admin
      hr.navbar-divider
      nuxt-link.navbar-item(to="/guide",@click.native="close")
        span.icon
          i.mdi.mdi-palette-swatch
        span Style Guide
      hr.navbar-divider
      NavBarLogout(:close="close")
</template>

<script>
import NavBarLogout from '@/components/layout/navbar/NavBarLogout'
import { mapGetters } from 'vuex'
export default {
  computed: { ...mapGetters(['isAdmin']), },
  components: { NavBarLogout },
  props: {
    close: {
      type: Function,
      required: true,
    }
  }
}
</script>

