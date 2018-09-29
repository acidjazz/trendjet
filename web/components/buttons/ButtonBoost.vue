<template lang="pug">
.content(v-if="boosted")
  .display-flex.is-vertical-center.justify-center
    div.ani-slide-in-left Video Boosted! &nbsp;
    nuxt-link.button.ani-slide-in-left.delay-1(:to="`/video/${id}`")
      span.icon
        i.mdi.mdi-history
      span Details
.field.has-addons.has-addons-centered(v-else)
  .control
    .select(:class="{'is-loading': boosting, 'is-primary': !boosting}")
      select(v-model="views")
        option(value=0) Select View Count
        option(v-for="value in boosts",:value="value")
          FormatNumber(:value="value",:text="true")
          | &nbsp;views
  .control
    ButtonLongPress.is-primary(:action="boost",:disabled="views == '' || boosting")
      span.icon
        i.mdi.mdi-rocket
      span Boost this Video!
</template>

<script>
import ButtonLongPress from '@/components/buttons/ButtonLongPress'
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { ButtonLongPress, FormatNumber },
  props: {
    id: {
      type: [Number, String],
      required: true,
    }
  },
  methods: {
    boost () {
      this.boosting = true
      this.$axios.post('boost', {video_id: this.id, views: this.views})
        .then( (response) => {
          this.boosted = true
          this.$toast.show(response.data.data)
        })
        .catch( (error) => {})
        .then( (resposne) => this.boosting = false)
    },
  },

  data () {
    return {
      views: 0,
      boosted: false,
      boosting: false,
      boosts: [
        100,
        1000,
        10000,
      ],
    }
  }
}
</script>
