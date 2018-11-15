<template lang="pug">
.content(v-if="boosted")
  .display-flex.is-vertical-center.justify-center
    div.ani-slide-in-left(v-if="is_many") Videos Boosted! &nbsp;
    div.ani-slide-in-left(v-else) Video Boosted! &nbsp;
    nuxt-link.button.ani-slide-in-left.delay-1(to="/boosts")
      span.icon: i.mdi.mdi-rocket
      span Boosts
.field.has-addons.has-addons-centered(v-else)
  .control
    .select(:class="{'is-loading': boosting, 'is-primary': !boosting}")
      select(v-model="views")
        option(value=0) Select View Count
        option(v-for="value in meta.options",:value="value")
          FormatNumber(:value="value",:text="true")
          | &nbsp;views
  .control
    ButtonLongPress.is-primary(:action="boost",:disabled="views == '' || boosting")
      span.icon
        i.mdi.mdi-rocket
      span(v-if="is_many") Boost these Videos!
      span(v-else) Boost this Video!
</template>

<script>
import ButtonLongPress from '@/components/buttons/ButtonLongPress'
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { ButtonLongPress, FormatNumber },
  props: {
    videos: {
      type: Array,
      required: true,
    }
  },
  methods: {
    boost () {
      this.boosting = true
      this.$axios.post('boost', {videos: this.ids(), views: this.views})
        .then( (response) => {
          this.boosted = true
          this.$toast.show(response.data.data)
        })
        .catch( (error) => {})
        .then( (resposne) => this.boosting = false)
    },

    async populate () {
      this.meta = (await this.$axios.get('/boost/meta')).data.data
    },

    ids () {
      let ids = []
      for (let video of this.videos) {
        ids.push(video.id)
      }
      return ids
    },
  },

  computed: {
    is_many () {
      return this.videos.length > 1
    },
  },

  created () {
    this.populate()
  },

  data () {
    return {
      views: 0,
      boosted: false,
      boosting: false,
      meta: {
        options: []
      },
    }
  }
}
</script>
