<template lang="pug">
#Profile.page
  BreadCrumbs(:crumbs="crumbs")
  br
  .container
    h1.title.is-size-6 Connected Devices
    ConnectedDevices
  br
  .container
    h1.title.is-size-6 Activity
    .box
      UserActivity
</template>

<script>
import BreadCrumbs from '@/components/layout/BreadCrumbs'
import ConnectedDevices from '@/components/pages/profile/ConnectedDevices'
import UserActivity from '@/components/pages/profile/UserActivity'
export default {
  middleware: [ 'is-auth' ],
  components: { BreadCrumbs, ConnectedDevices, UserActivity },
  data () {
    return {
      crumbs: [
        {
          name: 'Profile',
          icon: 'account-card-details',
          to: '/profile',
          active: true,
        },
      ]
    }
  }
}
</script>
