<template lang="pug">
.card
  VideoCardCover(:video="boost.video")
  ProgressBar(:total="boost.views",:progress="boost.delivered",:height="30",:on="boost.status !== 'pending'")
  .card-content
    .content
      BoostCardStatus(:status="boost.status")
      table.table.is-striped
        tr
          td Total Views
          td
            strong: FormatNumber(:value="boost.views") {{ boost.views }}
        tr
          td Delivered Views
          td
            strong: FormatNumber(:value="boost.delivered") {{ boost.delivered }}
        tr
          td Remaining Views
          td
            strong: FormatNumber(:value="boost.remaining") {{ boost.remaining }}
</template>

<script>
import VideoCardCover from '@/components/video/VideoCardCover'
import BoostCardStatus from '@/components/boost/BoostCardStatus'
import FormatNumber from '@/components/format/FormatNumber'
import ProgressBar from '@/components/loading/ProgressBar'
export default {
  components: { VideoCardCover, BoostCardStatus, FormatNumber, ProgressBar },
  props: {
    boost: {
      type: Object,
      required: true,
    }
  },
}
</script>
