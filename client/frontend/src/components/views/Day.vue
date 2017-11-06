<template>
	<div>
	
	<div class="cell">
		<div class="fc-day-number" :class="{ today: day.current }">

			{{day['num']}}
	
		</div>
		<div class="fc-day-content"><div>&nbsp;</div>
		</div>
    </div>
	<ul>
		<li v-for="event in day.events">
			<a v-if="event.id_user == user_id || $store.is_admin" href="#" @click="openModal(event.id)">{{$parent.$parent.DATE.formattedDate(event.time_start, event.time_end, $store.time_format)}}</a>
			<span v-else>{{$parent.$parent.DATE.formattedDate(event.time_start, event.time_end, $store.time_format)}}</span>
		</li>
	</ul>
	</div>
</template>

<script>

export default {
  name: 'day',
  props:['day'],
  data () {
    return {
      msg: 'day',
      user_id: this.$parent.$parent.$parent.$parent.user.id
    }
  },
  methods: {
		openModal: function (id) {
			window.open('/#/event/edit/' + id, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
		}
  }
  
}
</script>

<style>

</style>
