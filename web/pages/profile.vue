<template lang="pug">
#Profile.page
  section.section
    .container
      h2.subtitle Connected Devices
      ConnectedDevices
</template>

<script>
import ConnectedDevices from '@/components/pages/profile/ConnectedDevices'
export default {
  middleware: [ 'is-auth' ],
  components: { ConnectedDevices },
}
</script>
