<template lang="pug">
#Sessions.page-admin
  .container
    ConnectedDevices(:admin="true")
</template>

<script>
import ConnectedDevices from '@/components/pages/profile/ConnectedDevices'
export default {
  middleware: ['is-admin'],
  components: { ConnectedDevices },
}
</script>
