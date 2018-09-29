<template lang="pug">
#Plans.page
  section.section
    .container
      .box
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

</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
import ButtonLongPress from '@/components/buttons/ButtonLongPress'
export default {

  components: { FormatNumber, ButtonLongPress },

  methods: {
    async get () {
      this.packages = (await this.$axios.get('/package')).data.data
    },
    purchase (id) {
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
    }
  }

}
</script>
