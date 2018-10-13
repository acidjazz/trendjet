<template lang="pug">
#AdminDashboard.page.page-admin
  section
    .container
      .box
        .level
          .level-item.has-text-centered
            div
              p.heading Users
              p.title
                FormatNumber(:value="totals.users")
          .level-item.has-text-centered
            div
              p.heading Videos
              p.title
                FormatNumber(:value="totals.videos")
          .level-item.has-text-centered
            div
              p.heading Boosts
              p.title
                FormatNumber(:value="totals.boosts")

</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { FormatNumber },
  middleware: ['is-admin'],
  data () {
    return {
      totals: {
        users: 0,
      }
    }
  }
}
</script>
