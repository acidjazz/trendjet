<template lang="pug">
.card
  .card-image.is-loading
    figure.image.is-16by9
  .card-content
    .field.is-grouped.is-grouped-multiline
      .control
        .tags.has-addons
          span.tag.is-dark Views
          span.tag.is-info.is-loading &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      .control
        .tags.has-addons
          span.tag.is-dark Added
          span.tag.is-info.is-loading &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  footer.card-footer
    .card-footer-item.has-text-grey
      span.icon
        i.mdi.mdi-history
      span Details
    .card-footer-item.has-text-grey
      span.icon
        i.mdi.mdi-rocket
      span Boost 
    .card-footer-item.has-text-grey
      span.icon
        i.mdi.mdi-delete
      span Delete 

</template>

<script>
export default {
}
</script>
