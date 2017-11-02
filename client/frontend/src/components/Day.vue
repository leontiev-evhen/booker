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
			<a href="#" @click="openModal(event.id)">{{formattedDate(event.time_start, event.time_end)}}</a>
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
    }
  },
  methods: {
		openModal: function (id) {
			window.open('/#/admin/event/edit/' + id, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
		},
		formattedDate: function (date_start, date_end) {
			return new Date(date_start * 1000).getHours() + ':' + new Date(date_start * 1000).getMinutes() + ' - ' + new Date(date_end * 1000).getHours() + ':' + new Date(date_end * 1000).getMinutes()
		}
  }
}
</script>

<style>

</style>
