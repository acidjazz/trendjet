<template lang="pug">
#Videos.page
  section.section
    .container
      .level
        .level-left
          .level-item 16 Videos
        .level-right
          .level-item
            AddVideo
</template>

<script>
import AddVideo from '@/components/video/AddVideo'
export default {
  components: { AddVideo }
}
</script>
