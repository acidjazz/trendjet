<template lang="pug">
#Home.page
  .container
    section.section
      .buttons.is-centered
        .button(@click="modal") show modal
        .button(@click="message") show message
</template>

<script>
export default {
  methods: {
    modal () {
      this.$modal.show({title: 'testing', body: 'this is a test', buttons: [{name: 'OK'}] })
    },

    message () {
      this.$message.show({
        type: 'success', 
        message: `this is a test message`}
      )
    },
  },

  data () {
    return {
      counter: 0,
      types: [ 'success', 'info', 'warning', 'danger' ],
    }
  },
}
</script>


