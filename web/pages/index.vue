<template lang="pug">
#Home.page
  .container
    section.section
      .button(@click="showModal") show modal

</template>

<script>
export default {
  methods: {
    showModal () {
      this.$modal.alert('this is a test')
    },
  },
}
</script>
