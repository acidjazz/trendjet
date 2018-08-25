<template>
<svg class="spinner" :width="size" :height="size" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
   <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
</svg>
</template>

<script>
export default {
  props: {
    size: {
      type: String,
      required: false,
      default: '3rem',
    }
  }
}
</script>

<style lang="stylus">

@import '../../assets/stylus/includes/*'

offset = 187
duration = 1.4s

.spinner
  animation rotator duration linear infinite

@keyframes rotator
  0% 
    transform rotate(0deg)
  100% 
    transform rotate(270deg)

.path
  stroke-dasharray offset
  stroke-dashoffset 0
  transform-origin center
  animation dash duration ease-in-out infinite, colors (duration*4) ease-in-out infinite

@keyframes colors 
  0% 
    stroke pattens
  25% 
    stroke sail
  50% 
    stroke picton
  75% 
    stroke malibu
  100% 
    stroke sky

@keyframes dash 
  0% 
    stroke-dashoffset offset
  50% 
    stroke-dashoffset (offset/4)
    transform rotate(135deg)
  100%
    stroke-dashoffset offset
    transform rotate(450deg)

</style>
