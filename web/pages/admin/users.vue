<template lang="pug">
#Users.page
  section.section
    .columns(v-if="users.data").is-multiline
      .column.is-one-third(v-for="user in users.data")
        UserCard(:user="user")
    Paginate(v-if="users.paginate",:paginate="users.paginate",:query="query")
</template>

<script>
import UserCard from '@/components/admin/UserCard'
import Paginate from '@/components/buttons/Paginate'
export default {
  components: { UserCard, Paginate },
  middleware: ['is-admin'],
  methods: {
    async get (query) {
      this.users = (await this.$axios.get('/user', {params: query})).data
    },
    query (params) {
      let query = Object.assign({}, this.$route.query, params)
      this.$router.push({ query: query })
      this.get(query)
    },
  },
  mounted () {
    this.get(this.$route.query)
  },
  data () {
    return {
      users: {},
    }
  },
}
</script>
