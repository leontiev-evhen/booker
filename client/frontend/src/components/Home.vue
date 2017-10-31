<template>
  	<div class="home mt-20">
		<div class="row">
			<div class="col-md-9">
			<center>
				<button type="submit" class="btn btn-primary" @click="changeDay(0)">Sunday</button>
				<button type="submit" class="btn btn-primary" @click="changeDay(1)">Monday</button>
			</center>
			<div id="calendar" class="fc fc-ltr">
				<table class="fc-header">
					<tbody>
						<tr>
							<td class="fc-header-left">
							  <span class="fc-header-title">
								 <h2>{{month_name}} {{year}}</h2>
							  </span>
							</td>
							<td class="fc-header-right">
								<span :data-month="month" :data-year="year" @click="changeMonth($event, -1)" class="fc-button fc-button-prev fc-state-default fc-corner-left">
									‹
								</span>
								<span :data-month="month" :data-year="year" @click="changeMonth($event, +1)" class="fc-button fc-button-next fc-state-default fc-corner-right">
									›
								</span>
								<span class="fc-header-space"></span>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="fc-content" style="position: relative;">
					<div class="fc-view fc-view-month fc-grid" style="position:relative" unselectable="on">
						<table class="fc-border-separate">
							<thead>
								<tr>
									<th v-for="name in days_name">{{name}}</th>
								</tr>
							</thead>
							<tbody v-html="table"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<p><button type="submit" class="btn btn-primary">Book it</button></p>
			<p><button type="submit" class="btn btn-primary">Employee list</button></p>
		</div>
		</div>
	

   		<div style="clear:both"></div> 
	</div>
</template>


<script>
//https://github.com/RayBeedrill/booker/blob/dev/client/src/components/Calendar.vue
import CalendarDate from '../libs/calendar-date'
export default {
  name: 'home',
  	data () {
	    return {
	    	date: new CalendarDate(), 
	    	year: '',
	    	month: '',
	    	month_name: '',
	    	table: '',
			start_week: 0,
			days_name: ['Sn', 'Mn', 'Tu', 'We', 'Th', 'Fr', 'St'],
			num: [1, 6],
	    }
  	},
  	methods: {
  		login: function() {
	      this.$emit('login')
	    },
	    logout: function() {
	        this.$emit('logout')
	    },

	    createTableHtml: function (year, month) {
	     	var last_day_month = new CalendarDate(year, month + 1, 0).getDate(),
	     		dateObj = new CalendarDate(year, month, last_day_month),
     			last_day = dateObj.getLastDayWeekMonth(last_day_month),
     			first_day = dateObj.getFirstDayWeekMonth(this.num[0]),
	     		table = '<tr class="fc-week">';

  	 		if (first_day != 0) {
			   for(var  i = 1; i < first_day; i++) table += '<td>';
			} else {
			   for(var  i = 0; i < 6; i++) table += '<td>';
			}

			for(var  i = 1; i <= last_day_month; i++) {
			   	if (i == dateObj.getDate() && dateObj.getFullYear() == new Date().getFullYear() && dateObj.getMonth() == new Date().getMonth()) {
			     	table += '<td class="fc-day fc-mon fc-widget-content fc-past"><div class="cell"><div class="fc-day-number today">' + i + 
			 		'</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div>';
			   	} else {
					table += '<td class="fc-day fc-mon fc-widget-content fc-past"><div class="cell"><div class="fc-day-number">' + i+ 
			 		'</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div>';
				}
				if (new Date(dateObj.getFullYear(),dateObj.getMonth(),i).getDay() == this.num[1]) {
					table += '<tr>';
				}
			}

			for(var  i = last_day; i < 7; i++) {
				table += '<td>&nbsp;';
			}

			
			this.month = dateObj.getMonth();
			this.year = dateObj.getFullYear();
			this.month_name = this.date.getNameMonth(dateObj.getMonth());
			return table;
	    },
	    changeMonth: function (event, operation) {
			console.log(event)
	    	this.table = this.createTableHtml(event.target.attributes["1"].value, +event.target.attributes["0"].value  + operation);
	    },
		changeDay: function (item) {

			if (item) {
				this.days_name = ['Mn', 'Tu', 'We', 'Th', 'Fr', 'St', 'Sn'];
				this.num = [0, 0];
			} else {
				console.log(this.num)
				this.days_name = ['Sn', 'Mn', 'Tu', 'We', 'Th', 'Fr', 'St'];
				this.num = [1, 6];
			}
		
			this.table = this.createTableHtml(this.year, this.month);
		}
  	},
  	created() {
  		this.year = this.date.getFullYear();
  		this.month = this.date.getMonth();
  		this.month_name = this.date.getNameMonth(this.month);
  		this.table = this.createTableHtml(this.date.getFullYear(), this.date.getMonth());
  	},
}
</script>


<style>

</style>
