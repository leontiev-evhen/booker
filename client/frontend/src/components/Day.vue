<template>
    <p>{{day['data']}}<br><a href="#" @click="openModal(1)">event</a></p>
</template>

<script>
export default {
  name: 'day',
  props:['day'],
  data () {
    return {
      msg: 'day',
    }
  },
  methods: {
		openModal: function (id) {
			window.open('/admin/event/edit/' + id, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
		}
  }
}
</script>

<style>

</style>
