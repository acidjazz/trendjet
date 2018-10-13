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
    a.card-footer-item.has-text-info
      span.icon
        i.mdi.mdi-login
      span Log In

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
  }
}
</script>
