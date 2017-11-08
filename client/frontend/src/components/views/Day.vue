<template>
	<div>
	
	<div class="cell"  >
		<div class="fc-day-number" :class="{ today: day.current }">

			{{day['num']}}
	
		</div>
		<div class="fc-day-content"><div>&nbsp;</div>
		</div>
    </div>
	<ul class="events_list">
		<li v-if="day.events" v-for="event in day.events">

			<a v-if="(event.id_user == user_id || $store.is_admin) && current_time <= event.time_start" href="#" @click="openModal(event.id)">
				<i class="fa fa-clock-o" aria-hidden="true"></i>
				{{DATE.formattedDate(event.time_start, event.time_end, $store.time_format)}}
			</a>
			
			<span v-else>
				<i class="fa fa-clock-o" aria-hidden="true"></i>
				{{DATE.formattedDate(event.time_start, event.time_end, $store.time_format)}}
			</span>
		</li>
	</ul>
	</div>
</template>

<script>
import CalendarDate from '../../libs/calendar-date'
export default {
  name: 'day',
  props:['day'],
  data () {
    return {
      DATE: new CalendarDate(),
      user_id: this.$parent.$parent.$parent.$parent.user.id,
	  current_time: 0
    }
  },
  methods: {
		openModal: function (id) {
			let self = this;
			var prev_page = window.open($parent.$parent.BASE_URL + '/#/event/edit/' + id, '_blank', 'location=yes,height=550,width=550,scrollbars=no,status=yes');
			prev_page.onbeforeunload = function () {
				self.$emit('editEvent');
			};
		}
  },
  created() {
		this.current_time =  this.DATE.toTimestamp((this.DATE.getMonth() + 1 )+"/" + this.DATE.getDate() +"/"+this.DATE.getFullYear() + " " + this.DATE.getHours() + ":" + this.DATE.getMinutes())
  }
  
}
</script>

<style>

</style>
