<template lang="pug">
#Video.page
  section.section
    .container
      p hello
      pre {{ video }}
</template>

<script>
export default {

  methods: {
    async get () {
      this.video = (await this.$axios.get(`/video/${this.$route.params.id}`)).data.data
    }
  },

  mounted () {
    this.get()
  },

  data () {
    return {
      loaded: false,
      video: {},
    }
  }
}
</script>
