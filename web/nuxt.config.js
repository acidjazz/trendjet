require('dotenv').config()
const pkg = require('./package')

module.exports = {
  // mode: 'universal',
  mode: 'spa',

  env: {
    API_URL: process.env.API_URL || 'https://api.trendjet.io/',
    ENV: process.env.ENV || 'undefined',
  },

  router: {
    middleware: [ 'auth' ],
  },

  /*
  ** Headers of the page
  */
  head: {
    title: pkg.name,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: pkg.description },
      { name: 'msapplication-TileColor', content: '#ffffff' },
      { name: 'theme-color', content: '#ffffff' },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: '/framework.css' },
      { rel: 'stylesheet', href: '/mdi.css' },
      { rel: 'apple-touch-icon', sizes: '180x180', href: '/apple-touch-icon.png' },
      { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicon-32x32.png' },
      { rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicon-16x16.png' },
      { rel: 'manifest', href: '/site.webmanifest' },
      { rel: 'mask-icon', href: '/safari-pinned-tab.svg', color: '#01b5fa' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Roboto' },
    ],
    script: [
      { src: 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js', integrity: 'sha256-9YAuB2VnFZNJ+lKfpaQ3dKQT9/C0j3VUla76hHbiVF8=', crossorigin: 'anonymous' },
      { src: 'https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js', integrity: 'sha256-LlHVI5rUauudM5ZcZaD6hHPHKrA7CSefHHnKgq+/AZc=', crossorigin: 'anonymous' },
      { src: 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js', integrity: 'sha256-9Nt2r+tJnSd2A2CRUvnjgsD+ES1ExvjbjBNqidm9doI=', crossorigin: 'anonymous' },
    ],

  },

  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#a3e3fd' },

  loadingIndicator: {
    name: 'folding-cube',
    color: '#a3e3fd',
    background: 'white',
  },

  /*
  ** Global CSS
  */
  css: [
    { src: 'assets/stylus/main.styl', lang: 'stylus' },
  ],
  extractCSS: true,

  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://github.com/nuxt-community/axios-module#usage
    '@nuxtjs/axios',
    // 'globals',
  ],

  axios: {
    credentials: true,
    init (axios, ctx) {
      axios.defaults.headers.common['Accept'] = 'application/json'
    },
  },

  globals: {
    components: [
      '@/components/global/Modal',
      '@/components/global/Message',
    ],
  },

  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    '@/plugins/ssl.js',
    '@/plugins/axios.js',
    '@/plugins/global-components.js',
  ],


  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend(config, ctx) {
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  }
}
