<template lang="pug">
transition(name="animodal",v-if="title !== false")
  .modal.is-active(v-if="active")
    .modal-background(@click="destroy")
    .modal-card
      .modal-card-head
        p.modal-card-title {{ title }}
        button.delete(@click="destroy")
      section.modal-card-body(v-html="body")

      footer.modal-card-foot.justify-flex-end(v-if="buttons")
        .buttons.is-right.has-addons
          button.button(
            v-for="button, index in buttons",
            :autofocus="index === 0",
            :class="button.class",
            ref="button",
            @click="action(button)"): span {{ button.name }}

transition(name="animodal",v-else)
  .modal.is-active(v-if="active")
    .modal-background(@click="destroy")
    .modal-content
      .box(v-html="body")
    button.modal-close.is-large(autofocus,@click="destroy")
</template>

<script>
import { removeElement } from '@/utils/helpers.js'
export default {
  name: 'GlobalModal',
  props: {
    body: {
      type: String,
      required: true,
      default: 'You need to specify a body',
    },
    title:  {
      type: [String, Boolean],
      required: true,
    },
    buttons: {
      type: [Array, Boolean],
      required: false,
      default: () => false,
    },
    closed: {
      type: Function,
      required: false,
      default: () => {},
    },
  },

  methods: {
    destroy () {
      this.active = false
      setTimeout( () => {
        this.$destroy()
        removeElement(this.$el)
        if (this.closed) this.closed()
      }, 500)
    },
    action (button) {
      if (button.action && typeof button.action === 'function') {
        button.action()
      }
      return this.destroy()
    },
  },

  mounted () {
    this.active = true
    setTimeout(() => {
      if (this.$refs && this.$refs.button) {
        this.$refs.button[0].focus()
      }
    }, 300)
  },

  data () {
    return {
      active: false,
    }
  }
}
</script>
