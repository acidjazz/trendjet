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
      nuxt-link.navbar-item(v-if="isAdmin",to="/admin")
        .level.is-mobile
          .level-left
            .level-item
              span.icon
                i.mdi.mdi-security
            .level-item Admin
      hr.navbar-divider
      NavBarLogout
</template>

<script>
import NavBarLogout from '@/components/layout/navbar/NavBarLogout'
import { mapGetters } from 'vuex'
export default {
  computed: { ...mapGetters(['isAdmin']), },
  components: { NavBarLogout },
}
</script>

