<template lang="pug">
.navbar-end
  .navbar-item.has-dropdown.is-hoverable 
    .navbar-link
      .level
        .level-left
          .level-item
            figure.image.is-32x32
              img.is-rounded(:src="$store.state.user.avatar")
          .level-item
            span {{ $store.state.user.name }}
    .navbar-dropdown.is-right
      hr.navbar-divider
      NavBarLogout
</template>

<script>
import NavBarLogout from '@/components/layout/navbar/NavBarLogout'
export default {
  components: { NavBarLogout }
}
</script>

