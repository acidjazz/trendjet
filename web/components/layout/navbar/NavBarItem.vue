<template lang="pug">
nuxt-link.navbar-item(
  @click.native="close",
  v-if="!connecting || mobile",
  to="/packages",
  :class="{'is-active': $route.name == 'plans'}")
  span.icon: i.mdi.mdi-package-variant
  span Packages
</template>

<script>
export default {
  props: {
    route: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      required: true,
    },
    connecting: {
      type: Boolean,
      required: true,
    },
    mobile: {
      type: Boolean,
      required: true,
    },
    close: {
      type: Function,
      required: true,
    },
  }
}
</script>
