<template lang="pug">
.navbar-item.has-dropdown(:class="{'is-active': active }")
  .navbar-link(@click="swap")
    .level
      .level-left
        .level-item
          figure.image.is-32x32
            .avatar.is-rounded(:style="`background-image: url(${$store.state.user.avatar})`")
        .level-item
          span {{ $store.state.user.name }}
          //ul
            li {{ $store.state.user.name }}
            li.is-size-7 {{ $store.state.user.views }} views
  .navbar-dropdown.is-right(:class="{'is-closing': closing}")

    nuxt-link.navbar-item(to="/profile",@click.native="off")
      span.icon
        i.mdi.mdi-account-card-details
      span Profile

    hr.navbar-divider

    nuxt-link.navbar-item(v-if="admin",to="/admin",@click.native="off")
      span.icon
        i.mdi.mdi-security
      span Admin

    nuxt-link.navbar-item(to="/guide",@click.native="off")
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
  computed: { ...mapGetters(['admin']), },
  components: { NavBarLogout },
  props: {
    close: {
      type: Function,
      required: true,
    },
  },
  methods: {
    swap () {
      if (this.active) {
        return this.off()
      }
      return this.on()
    },
    on () { this.active = true },
    off () {
      this.closing = true
      setTimeout(() => {
        this.closing = false
        this.active = false
        this.close()
      }, 400)
    }
  },

  data () {
    return {
      closing :false,
      active: false,
    }
  }
}
</script>

