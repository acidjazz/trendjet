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
import query from '@/mixins/query'
export default {
  components: { UserCard, Paginate },
  middleware: ['is-admin'],
  mixins: [ query ],
  methods: {
    async get (query) {
      this.users = {}
      this.users = (await this.$axios.get('/user', {params: query})).data
    }
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
