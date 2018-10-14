<template lang="pug">
#Profile.page
  section.section
    .container
      h2.subtitle Connected Devices
      UserActivity
</template>

<script>
import UserActivity from '@/components/pages/profile/UserActivity'
export default {
  middleware: [ 'is-auth' ],
  components: { UserActivity },
}
</script>
