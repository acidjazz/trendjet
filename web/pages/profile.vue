<template lang="pug">
#Profile.page
  section.section
    .container
      h2.subtitle Connected Devices
      ConnectedDevices

  section.section
    .container
      h2.subtitle Activity
      UserActivity
</template>

<script>
import ConnectedDevices from '@/components/pages/profile/ConnectedDevices'
import UserActivity from '@/components/pages/profile/UserActivity'
export default {
  middleware: [ 'is-auth' ],
  components: { ConnectedDevices, UserActivity },
}
</script>
