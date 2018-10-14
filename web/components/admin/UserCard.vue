<template lang="pug">
.card
  .card-content
    article.media
      figure.media-left
        p.image.is-64x64
          img(:src="user.avatar")
      .media-content
        .content
          div
            strong {{ user.name }} &nbsp;
          div
            small {{ user.email }}
    .content
      div
        strong created &nbsp;
        FormatDate(:value="user.created_at")
      div
        strong updated &nbsp;
        FormatDate(:value="user.updated_at")
  footer.card-footer
    a.card-footer-item
      span.icon
        i.mdi.mdi-account-card-details
      span Details
    a.card-footer-item.has-text-danger
      span.icon
        i.mdi.mdi-delete
      span Delete
    a.card-footer-item.has-text-info(@click="loginAs")
      span.icon
        i.mdi.mdi-login
      span Login As

</template>

<script>
import FormatDate from '@/components/format/FormatDate'
export default {
  components: { FormatDate },
  props: {
    user: {
      type: Object,
      required: true,
    }
  },

  methods: {
    async loginAs () {
      let response = await this.$axios.get(`/login/${this.user.id}`)
      if (response.data && response.data.data.success) {
        this.$toast.show(response.data.data)
        setTimeout( () => window.location = '/dashboard', 1000)
      }
    },
  },

}
</script>
