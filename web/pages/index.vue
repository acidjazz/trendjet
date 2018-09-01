<template lang="pug">
#Home.page
  .container
    section.section
      .content
        // code {{ cookies }}
      .buttons.is-centered
        .button(@click="modal") show modal
        .button(@click="message") show message
        .button(@click="loginas") login as acidjazz@gmail.com
</template>

<script>
export default {
  methods: {
    modal () {
      this.$modal.show({title: 'testing', body: 'this is a test', buttons: [{name: 'OK'}] })
    },

    message () {
      this.$message.show({
        type: this.types[Math.floor(Math.random()*this.types.length)], 
        message: `this is a test message`}
      )
    },

    async loginas () {
      let response = await this.$axios.get('/loginas/acidjazz@gmail.com')
      console.log(response.headers)
    },
  },

  mounted () {
    if (window.Cookies) {
      this.cookies = window.Cookies.get()
    }
  },

  data () {
    return {
      cookies: {},
      counter: 0,
      types: [ 'success', 'info', 'warning', 'danger' ],
    }
  },
}
</script>


