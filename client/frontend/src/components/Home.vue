<template>
  	<div id="wrap">
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
		               		<span class="fc-button fc-button-prev fc-state-default fc-corner-left" unselectable="on">
		               			<span :data-month="month" :data-year="year" class="fc-text-arrow" @click="changeMonth($event, -1)">‹</span>
		               		</span>
		               		<span class="fc-button fc-button-next fc-state-default fc-corner-right" unselectable="on">
		               			<span :data-month="month" :data-year="year" class="fc-text-arrow" @click="changeMonth($event, +1)">›</span>
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
					    	<tr><td>Sn</td><td>Mn</td><td>Tu</td><td>We</td><td>Th</td><td>Fr</td><td>St</td></tr>
					    </thead>
					    <tbody v-html="table"></tbody>
					</table>
		        </div>
	      	</div>
	   	</div>
   		<div style="clear:both"></div> 
	</div>
</template>


<script>
import CalendarDate from '../libs/calendar-date'
export default {
  name: 'home',
  	data () {
	    return {
	    	date: new CalendarDate(), 
	    	year: '',
	    	month: '',
	    	month_name: '',
	    	table: ''
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
	  //   	var last_day_month = new Date(year,month+1,0).getDate(),
	  //   		dateObj = new CalendarDate(year, month, last_day_month),
   //  			last_day = dateObj.getLastDayWeekMonth(last_day_month),
   //  			first_day = dateObj.getFirstDayWeekMonth(),
	  //   		table = '<tr class="fc-week">';

  	// 		if (first_day != 0) {
			//   for(var  i = 1; i < first_day; i++) table += '<td>';
			// }else{
			//   for(var  i = 0; i < 6; i++) table += '<td>';
			// }

			// for(var  i = 1; i <= last_day_month; i++) {
			//   	if (i == dateObj.getDate() && dateObj.getFullYear() == dateObj.getFullYear() && dateObj.getMonth() == dateObj.getMonth()) {
			//     	table += '<td class="fc-day fc-mon fc-widget-content fc-past"><div class="cell"><div class="fc-day-number today">' + i+ 
			// 		'</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div>';
			//   	} else {
			// 		table += '<td class="fc-day fc-mon fc-widget-content fc-past"><div class="cell"><div class="fc-day-number">' + i+ 
			// 		'</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div>';
			//   }
			//   if (new Date(dateObj.getFullYear(),dateObj.getMonth(),i).getDay() == 6) {
			//     table += '<tr>';
			//   }

			// }

			// for(var  i = last_day; i < 7; i++) {
			// 	table += '<td>&nbsp;';
			// }

			// this.month = month;
			// this.year = year;
			var Dlast = new Date(year,month+1,0).getDate(),
    D = new Date(year,month,Dlast),
    DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
    DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay() + 1,
    calendar = '<tr>';
  


if (DNfirst != 0) {
  for(var  i = 1; i < DNfirst; i++) calendar += '<td>';
}else{
  for(var  i = 0; i < 6; i++) calendar += '<td>';
}

for(var  i = 1; i <= Dlast; i++) {
  if (i == new Date().getDate() && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
    calendar += '<td class="today">' + i;
  }else{
	

		calendar += '<td>' + i;

    
  }
  if (new Date(D.getFullYear(),D.getMonth(),i).getDay() == 6) {
    calendar += '<tr>';
  }

}
for(var  i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
	this.month = D.getMonth();
	this.year = D.getFullYear();
			return calendar;
	    },
	    changeMonth: function (event, operation) {
	    
	    	this.month += operation
	    	this.month_name = this.date.getNameMonth(this.month);
	    	this.table = this.createTableHtml(event.target.attributes["1"].nodeValue, +event.target.attributes["0"].nodeValue  + operation);
	    	return this.table;
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
