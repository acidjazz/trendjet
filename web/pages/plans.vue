<template lang="pug">
#Plans.page
  section.section
    .container
      .box
        .pricing-table
          .pricing-plan.ani-zoom-in(
            v-for="plan, index in plans",
            :class="[`${styles[plan.title]} delay-${index+1}`, {'is-active': index === active}]")
            .plan-header {{ plan.title }}
            .plan-price
              span.plan-price-amount
                span.plan-price-currency $
                FormatNumber(:value="plan.price")
            .plan-items
              .plan-item
                FormatNumber(:value="plan.views")
                | &nbsp;Views
              .plan-item SmartView Guarantee
            .plan-footer
              ButtonLongPress(
                label="Boost",
                :action="() => { purchase(plan.id); }",
                :theme="plan.title === 'Tester' ? 'dark' : 'light'")
</template>

<script>
import FormatNumber from '@/components/format/FormatNumber'
import ButtonLongPress from '@/components/buttons/ButtonLongPress'
export default {

  components: { FormatNumber, ButtonLongPress },

  methods: {
    async get () {
      this.plans = (await this.$axios.get('/plan')).data.data
    },
    purchase (id) {
      this.$axios.post('/purchase', {plan_id: id})
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
      plans: [],
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
