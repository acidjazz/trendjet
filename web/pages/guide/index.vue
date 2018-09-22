<template lang="pug">
#Guide.page
    section.hero.is-primary
      .container
        .hero-body
          .container
            h1.title trendjet style guide
            h2.subtitle colors, fonts, and components
    section.section
      .content
        h2 Colors
        hr.divider.has-background-primary
        .box
          .columns.is-multiline
            .column(v-for="hex, name in colors")
              .notification(
                :style="`background-color: ${hex};`"
                :class="{'has-text-white': light.indexOf(name) != -1}")
                p {{ name }}
                p {{ hex }}

        h2 Fonts
        hr.divider.has-background-primary
        .box
          p trendjet uses Google's
            a(href="https://fonts.google.com/specimen/Roboto") roboto
            | font
            ul
              li(v-for="n in 7",:class="`is-size-${n}`") size {{ n }}
        h2 Global Programmatic Components
        hr.divider.has-background-primary

        h3 Modal
        pre: code.language-js.
          this.$modal.show('this is a simple modal')
        .button(@click="modal") show modal

        h3 Detailed Modal
        pre: code.language-js.
          this.$modal.show({
            title: 'title',
            body: 'this is the body',
            buttons: [
              {name: 'OK', class: 'is-primary'},
              {name: 'Cancel'},
            ]})
        .button(@click="modalDetailed") show detailed modal

        h3 Toast
        pre: code.language-js.
          this.$toast.show('this is a toast')
        .button(@click="toast") show toast

        h3 Detailed Toast
        pre: code.language-js.
          let types = ['default','success','info','danger']
          let type = types[Math.floor(Math.random() * types.length)]
          this.$toast.show({
            type: type,
            message: `this toast is type ${type}`})
        .button(@click="toastDetailed") show toast


</template>

<script>
export default {
  methods: {
    modal () {
      this.$modal.show('this is a simple modal')
    },
    modalDetailed () {
      this.$modal.show({
        title: 'title',
        body: 'this is the body',
        buttons: [
          {name: 'OK', class: 'is-primary'},
          {name: 'Cancel'},
        ]})
    },

    toast () {
      this.$toast.show('this is a toast')
    },
    toastDetailed () {
      let types = ['default','success','info','danger']
      let type = types[Math.floor(Math.random() * types.length)]
      this.$toast.show({
        type: type,
        message: `this toast is type ${type}`})
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
      colors: {
        picton: '#01b6fa',
        malibu: '#01c2f9',
        sky: '#60d4fd',
        sail: '#a3e3fd',
        pattens: '#d9f2fe',
        squeeze: '#F4F7FA',
        romance: '#fefefe',
        zircon: '#DBE0E8',
      },
      light: [ 'picton','malibu' ],
    }
  },

  head () {
    return {
      link: [
        { rel: 'stylesheet', href: 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.15.0/themes/prism.min.css' },
        { rel: 'stylesheet', href: 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.15.0/themes/prism-okaidia.min.css' },
      ],
      script: [
        { src: 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.15.0/prism.min.js' },
      ]
    }
  },
}
</script>


