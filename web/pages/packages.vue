<template lang="pug">
#Packages.page
  BreadCrumbs(:crumbs="crumbs")
  section.section
    .container
      //.box(style="min-height: 347px;")
      .pricing-table
        .pricing-plan.ani-zoom-in(
          v-for="pkg, index in packages",
          :class="[`${styles[pkg.title]} delay-${index+1}`, {'is-active': index === active}]")
          .plan-header {{ pkg.title }}
          .plan-price
            span.plan-price-amount
              span.plan-price-currency $
              FormatNumber(:value="pkg.price")
          .plan-items
            .plan-item
              FormatNumber(:value="pkg.views")
              | &nbsp;Views
            .plan-item SmartView Guarantee
          .plan-footer
            ButtonLongPress(
              :action="() => { purchase(pkg.id); }",
              position="center",
              :theme="pkg.title === 'Tester' ? 'dark' : 'light'")
              span.icon
                i.mdi.mdi-rocket
              span Get Package
  section.section
    //form(action="your-server-side-code",method="POST")
    script.stripe-button(
      src="https://checkout.stripe.com/checkout.js"
      data-key="pk_test_oHkrGuCGFuJAM0t6PI2wyY0m"
      data-amount="999"
      data-name="trendjet"
      data-description="Tester Package"
      data-image="/android-chrome-192x192.png"
      data-locale="auto")
</template>

<script>
import { mapGetters } from 'vuex'
import BreadCrumbs from '@/components/layout/BreadCrumbs'
import FormatNumber from '@/components/format/FormatNumber'
import ButtonLongPress from '@/components/buttons/ButtonLongPress'
export default {

  components: { BreadCrumbs, FormatNumber, ButtonLongPress },
  computed: {
    ...mapGetters(['auth']),
  },
  methods: {
    async get () {
      this.packages = (await this.$axios.get('/package')).data.data
    },
    purchase (id) {
      if (!this.auth) {
        return this.$toast.show({
          type: 'danger',
          message: 'Please connect before purchasing a package.',
        })
      }
      this.$axios.post('/purchase', {package_id: id})
        .then( (response) => {
          this.$toast.show(response.data.data)
          this.$store.dispatch('refresh')
        })
    },
  },

  mounted () {
    this.get()
  },

  data () {
    return {
      packages: [],
      active: 1,
      styles: {
        Tester: '',
        Starter: 'is-info',
        Growing: 'is-success',
        Enterprise: 'is-primary',
      },
      crumbs: [
        {
          name: 'Packages',
          icon: 'package-variant',
          to: '/packages',
          active: true,
        },
      ],
    }
  }

}
</script>
