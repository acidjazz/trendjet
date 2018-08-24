<template lang="pug">
transition(name="animessage")
  .message(:class="`is-${type}`",v-if="active")
    .message-body
      button.delete(@click="destroy")
      .media
        .media-left
          span.icon.is-large(:class="`is-${type}`")
            i.mdi.mdi-48px(:class="icon")
        .media-content.align-self-center
          span(v-html="message")
              
</template>

<script>
import { removeElement } from '@/utils/helpers.js'
export default {
  props: {
    message: {
      type: String,
      required: true,
      default: 'Please specify a <b>message</b>',
    },
    type: {
      type: String,
      required: false,
      validate: (type) => { return ['sucecss','info','danger', 'warning'].indexOf(type) !== -1 }
    },
  },

  methods: {
    destroy () {
      this.active = false
      setTimeout( () => {
        this.$destroy()
        removeElement(this.$el)
      }, 500)
    },
  },
  mounted () {
    this.active = true
    setTimeout(() =>  this.destroy(), 3000)
  },

  computed: {
    icon () {
      switch (this.type) {
        case 'success' :
          return 'mdi-check-circle'
          break
        case 'danger' :
          return 'mdi-alert-circle'
          break
        case 'info' :
          return 'mdi-information'
          break
        case 'warning' :
          return 'mdi-alert'
          break
      }
    }
  },


  data () {
    return {
      active: false,
    }
  },
}
</script>
