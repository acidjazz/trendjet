<template lang="pug">
span
  span Sent boost to&nbsp;
  figure.image.is-16by9.yt-bg.tooltip(
    :data-tooltip="value.payload.video.title",
    :style="`background-image: url(${value.payload.video.cover});`")
  span &nbsp;with&nbsp;
  strong: FormatNumber(:value="value.payload.boost.views")
  span &nbsp;views
</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
export default {
  components: { FormatNumber },
  props: {
    value: {
      type: Object,
      required: true,
    }
  },
}
</script>
