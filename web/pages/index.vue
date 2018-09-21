<template lang="pug">
#Home.page
  section.section
    .container
      .columns(v-if="auth")
        .column.is-one-third
          .box.ani-zoom-in
            nav.level
              .level-item.has-text-centered
                div
                  p.heading Videos
                  p.title: FormatNumber(:value="dashboard.videos")
        .column.is-one-third
          .box.ani-zoom-in
            nav.level
              .level-item.has-text-centered
                div
                  p.heading Views
                  p.title: FormatNumber(:value="dashboard.views")
        .column.is-one-third
          .box.ani-zoom-in
            nav.level
              .level-item.has-text-centered
                div
                  p.heading Boosts
                  p.title: FormatNumber(:value="dashboard.boosts")

      .box.ani-zoom-in(v-else)
        .level(style="width: 300px; margin: auto;")
          .level-left
            .level-item
              figure.image
                img(src="/logo64.png")
            .level-item
              h1.is-size-1.has-text-primary trendjet
</template>


<script>
import FormatNumber from '@/components/format/FormatNumber'
import { mapGetters } from 'vuex'

export default {
  computed: { ...mapGetters(['auth']), },
  components: { FormatNumber },
  methods: {
    rando () {
      this.dashboard.videos = Math.random() * 100000
      this.dashboard.views = Math.random() * 100000
      this.dashboard.boosts = Math.random() * 100000
    }
  },

  mounted () {
    setTimeout(this.rando, 2000)
  },


  data () {
    return {
      dashboard: {
        videos: 0,
        views: 0,
        boosts: 0,
      }
    }
  }
}
</script>
