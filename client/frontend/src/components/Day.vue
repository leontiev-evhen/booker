<template>
	<div>
	
	<div class="cell">
		<div class="fc-day-number" :class="{ today: day.current }">

			{{day['data']}}

		</div>
		<div class="fc-day-content"><div>&nbsp;</div>
		</div>
    </div>
	<ul>
		<li v-for="event in day.events">
			<a v-if="event.id_user == user_id" href="#" @click="openModal(event.id)">{{formattedDate(event.time_start, event.time_end)}}</a>
			<span v-else>{{formattedDate(event.time_start, event.time_end)}}</span>
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
		},
		formattedDate: function (date_start, date_end) {
			var time_start = new Date(date_start * 1000);
			var time_end = new Date(date_end * 1000);
			var hour;
			var min;

			// if (this.$store.time_format == 24) {
			// 	hour = date_start.getHours() + 12;
			// } else {
			// 	hour = date_start.getHours();
			// }

			// if (date_start.getMinutes() == 0) {
			// 	min = date_start.getHours();
			// } else {
			// 	min = date_start.getMinutes();
			// }

			return time_start.getHours() + ':' + time_start.getMinutes() + ' - ' + time_end.getHours() + ':' + time_end.getMinutes()
		}
  }
}
</script>

<style>

</style>
