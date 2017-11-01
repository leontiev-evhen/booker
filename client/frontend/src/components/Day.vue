<template>
    <p>{{day['data']}}</p>
</template>

<script>
export default {
  name: 'day',
  data () {
    return {
      msg: 'day',
    }
  },
  props:['day']
}
</script>

<style>

</style>
