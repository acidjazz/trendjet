<template lang="pug">
nuxt-link.navbar-item(
  @click.native="close",
  :to="`/${route}`",
  :class="{'is-active': $route.name == route}")
  span.icon: i.mdi(:class="`mdi-${icon}`")
  span {{ label }}
</template>

<script>
export default {
  props: {
    route: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      required: true,
    },
    close: {
      type: Function,
      required: true,
    },
  }
}
</script>
