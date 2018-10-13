<template lang="pug">
#trendjet
  #toasts
  #modals
  NavBar
  AdminNavBar(v-if="isAdmin")
  nuxt
</template>

<script>
import NavBar from '@/components/layout/navbar/NavBar.vue'
import AdminNavBar from '@/components/layout/navbar/AdminNavBar.vue'
export default {
  components: { NavBar, AdminNavBar } ,
  computed: {
    isAdmin () {
      return this.$route.name.substr(0, 5) === 'admin'
    }
  },
}
</script>

