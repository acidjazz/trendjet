<template lang="pug">
.navbar-end
  .navbar-item.has-dropdown(:class="{'is-active': active }",v-on-clickaway="off")
    .navbar-link(@click="swap")
      .level
        .level-left
          .level-item
            figure.image.is-32x32
              img.avatar.object-cover.ani-zoom-in(:src="$store.state.user.avatar")
          .level-item
            span {{ $store.state.user.name }}
    .navbar-dropdown.is-right(:class="{'is-closing': closing}")

      nuxt-link.navbar-item(to="/videos",@click.native="off")
        span.icon
          i.mdi.mdi-youtube
        span My Videos
 
      nuxt-link.navbar-item(v-if="isAdmin",to="/admin",@click.native="off")
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
import { mixin as clickaway } from 'vue-clickaway'
export default {
  computed: { ...mapGetters(['isAdmin']), },
  components: { NavBarLogout },
  mixins: [ clickaway ],
  props: {
    close: {
      type: Function,
      required: true,
    }
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

