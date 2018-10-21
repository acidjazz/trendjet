<template lang="pug">
.page-breadcrumbs.has-background-white
  .container
    nav.breadcrumb.is-medium.has-arrow-separator
      ul
        li(v-for="crumb in crumbs",:class="{'is-active': crumb.active}")
          nuxt-link(:to="crumb.to ? crumb.to : ''")
            span.icon.is-small(v-if="crumb.icon")
              i.mdi(:class="`mdi-${crumb.icon}`")
            span {{ crumb.name }}
</template>

<script>
export default {
  props: {
    crumbs: {
      type: Array,
      required: true,
    }
  }
}
</script>
