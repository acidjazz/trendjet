<template lang="pug">
transition(name="animodal")
  .modal.is-active(v-if="isActive")
    .modal-background
    .modal-card
      .modal-card-head
        p.modal-card-title Check your Inbox
        button.delete(@click="destroy")
      section.modal-card-body
        p An e-mail was sent to your account with a link to login.
        hr
        p You may 
          b close this window/tab now
          |, since clicking the link will open a new one!
        hr
        p {{ message }}
      footer.modal-card-foot.justify-flex-end
        .buttons.is-right
          .button.button OK
</template>

<script>
export default {
  name: 'GlobalModal',
  props: {
    message: String
  },
  methods: {
    destroy() {
      if (typeof (this.$el).remove !== 'undefined') {
        this.$el.remove()
      } else {
        this.$el.parentNode.removeChild(el)
      }
    },
  },

  mounted () {
    console.log(this.message)
    this.isActive = true
  },
  data () {
    return {
      isActive: false,
    }
  }
}
</script>

