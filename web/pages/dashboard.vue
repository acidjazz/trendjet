<template lang="pug">
#Dashboard.page
  BreadCrumbs(:crumbs="crumbs")
  section.section
    .container
      .columns
        .column.is-one-third
          .box.ani-zoom-in
            nav.level
              .level-item.has-text-centered
                div
                  p.heading Videos
                  p.title: FormatNumber(:value="$store.state.user.stats.videos")
        .column.is-one-third
          .box.ani-zoom-in
            nav.level
              .level-item.has-text-centered
                div
                  p.heading remaining views
                  p.title: FormatNumber(:value="$store.state.user.views")
        .column.is-one-third
          .box.ani-zoom-in
            nav.level
              .level-item.has-text-centered
                div
                  p.heading total boosts
                  p.title: FormatNumber(:value="dashboard.boosts")
</template>


<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
import FormatNumber from '@/components/format/FormatNumber'
import { mapGetters } from 'vuex'

export default {
  middleware: [ 'is-auth' ],
  computed: { ...mapGetters(['auth']), },
  components: { BreadCrumbs, FormatNumber },

  mounted () {
  },


  data () {
    return {
      dashboard: {
        videos: 0,
        views: 0,
        boosts: 0,
      },
      crumbs: [
        {
          name: 'Dashboard',
          icon: 'view-dashboard',
          to: '/dashboard',
          active: true,
        },
      ]
    }
  }
}
</script>
