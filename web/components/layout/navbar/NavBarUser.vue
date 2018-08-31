<template lang="pug">
.navbar-end
  .navbar-item.has-dropdown(:class="{'is-active': active}")
    a.navbar-link(@click="active = !active")
      .level
        .level-left
          .level-item
            figure.image.is-24x24
              img.is-rounded(:src="$store.state.user.avatar")
          .level-item
            span {{ $store.state.user.name }}
    .navbar-dropdown.is-right
      hr.navbar-divider
      NavBarLogout(:close="close")
</template>

<script>
import NavBarLogout from '@/components/layout/navbar/NavBarLogout'
export default {
  components: { NavBarLogout },
  methods: {
    close () {
      this.active = false
    },
  },
  data () {
    return {
      active: false,
    }
  },
}
</script>

