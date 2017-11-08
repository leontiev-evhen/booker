<template>
  	<div id="home" class="mt-20">
		<div class="row">
	      <div class="col-md-9">
				<div class="fc fc-ltr " id="calendar">	
					<div class="fc-view fc-view-month fc-grid">
						<table class="fc-header">
							<tbody>
								<tr>
									<td class="fc-header-left">
									  <span class="fc-header-title">
										 <h2><i class="fa fa-calendar" aria-hidden="true"></i> {{month}} {{year}}</h2>
									  </span>
									</td>
									<td class="fc-header-center">
										{{room_name}}
									</td>

									<td class="fc-header-right">
										<span @click="changeTimeFormat" class="fc-button fc-button-prev fc-state-default format">
											Time format {{$store.time_format}}
										</span>
										<span @click="changeFormat" class="fc-button fc-button-prev fc-state-default format">
											Format <img :src="$parent.$parent.BASE_URL + '/static/images/' + format + '.png'" class="format_img">
										</span>
										<span @click="changeMonth(-1)" class="fc-button fc-button-prev fc-state-default fc-corner-left">
											‹
										</span>
										<span @click="changeMonth(+1)" class="fc-button fc-button-next fc-state-default fc-corner-right">
											›
										</span>
										<span class="fc-header-space"></span>
									</td>
								</tr>
							</tbody>
						</table>
						<month :months="calendar_days" :format="format" @editEvent="editEvent"></month>
		   		</div>
		      </div>

	          	
	      </div>

	      <div class="col-md-3">
	      	<center class="mt-15">
					<a v-if="room_id" :href="$parent.$parent.BASE_URL + '/#/event/create/room/' + room_id" class="btn btn-brown">
						Book it 
						<i class="fa fa-plus-circle" aria-hidden="true"></i>
					</a>
					<a v-if="$store.is_admin" :href="$parent.$parent.BASE_URL + '/#/users'" class="btn btn-brown">
						Employee list
						<i class="fa fa-list" aria-hidden="true"></i>
					</a>
				</center>
				<h3>List rooms</h3>
				<ul class="list_rooms">
					<li v-for="room in rooms">
						<i class="fa fa-briefcase" aria-hidden="true"></i> <a href="#" @click="getRoom(room.id, room.name)">{{room.name}}</a>
					</li>
				</ul>
				
			</div>
		</div>
      
    </div>
</template>

<script>

import CalendarDate from '../../libs/calendar-date'
import Month from './Month'
export default {
  	name: 'home',
  	data () {
    	return {
	      month:'',
	      calendar_days:[],
	      DATE: new CalendarDate(),
	      year: '',
	      month_num: '',
	      format:'ua',
	      time_format: this.$store.time_format,
	      rooms: '',
	      room_name: '',
	      room_id: '',
	      events: []
    	}
  	},
  	created:function() {

  		this.year = this.DATE.getFullYear();
  		this.month_num = this.DATE.getMonth();
    	this.getMonthArr(this.year, this.month_num)

    	//get all rooms
    	this.axios.get(this.$parent.$parent.AJAX_URL + '/booker/client/api/rooms').then((response) => {

			if (response.status == 200) {
				if (response.data.success) {
					this.rooms = response.data.data;
					this.getRoom(this.rooms[0].id, this.rooms[0].name);
				} 
			} else {
				console.log(response.data.message)
			}
		})
  	},
	methods: {
		editEvent: function () {
			this.getEvents();
		},
    	getMonthArr: function (year, month, format = 'ua' ) {
		
			  this.calendar_days = [];
	
			  let date = new Date(year, month);
			  
			  this.month = this.DATE.getNameMonth(month);
			  let clearDay = 0; 
			  this.calendar_days[0] = [];
			  for (let i = 0; i < this.getDay(date,format); i++) {
				  this.calendar_days[0].push({});
				  clearDay ++
			  }
	
			  let row = 0;
			 
			  let i = 1;

			  while (date.getMonth() == month) {
				let current = false;  
				let events = [];
				  if (
				  i == this.DATE.getDate() && 
				  this.DATE.getMonth() == date.getMonth() && 
				  this.DATE.getFullYear() == date.getFullYear()) {
						current = true;						
				  } 
				  
				  
				  if (this.events) {
					  for (var k in this.events) {
						if (new Date(this.events[k].time_start * 1000).getDate() == i && new Date(this.events[k].time_start * 1000).getMonth() == month) {
							events.push(this.events[k])
						}
					  }
				  }
				  this.calendar_days[row].push({num: date.getDate(), current: current, events: events})
				if (this.getDay(date,format) % 7 == 6) { 
				  row ++
				  this.calendar_days[row] = []
				}
				date.setDate(date.getDate() + 1);
				i++;
			  }
			  
			  return this.calendar_days
      
		},

	   getDay: function (date, format) {
         var day = date.getDay();
         if(format == 'ua') {
           if (day == 0) day = 7;
           return day -1;
         }
         return day;
	   },


		changeMonth: function (operation) {
			this.month_num += operation
	      
			if (this.month_num < 0) {
				this.month_num = 11
				this.year--
			}

			if (this.month_num > 11) {
				this.month_num = 0
				this.year++
			}
			
			this.getEvents();
			this.calendar_days = this.getMonthArr(this.year, this.month_num);
	    },

	   changeFormat: function () {
	      if (this.format == 'ua') {
	        this.format = 'usa';
	        this.calendar_days = this.getMonthArr(this.year, this.month_num,'usa');
	      } else {
	        this.format = 'ua';
	        this.calendar_days = this.getMonthArr(this.year, this.month_num,'ua');
	      }
	    },

	    changeTimeFormat: function () {

	      	if (localStorage['time_format'] == 24) {
	       		//this.time_format = 12;
				this.$store.time_format = 12;
	       		localStorage['time_format'] = 12;
	       		this.calendar_days = this.getMonthArr(this.year, this.month_num, this.format);
	      	} else {
	        	//this.time_format = 24;
				this.$store.time_format = 24;
	        	localStorage['time_format'] = 24;
	        	this.calendar_days = this.getMonthArr(this.year, this.month_num, this.format);
	     	}
	   	},

	   getRoom: function (id, name) {
			this.room_name = name;
	   		this.room_id = id;
	   		this.getEvents();
	   },

	   getEvents: function () {

    		this.axios.get(this.$parent.$parent.AJAX_URL + '/booker/client/api/events/room/' + this.room_id + '/month/' + this.month_num + '/year/' + this.year).then((response) => {

				if (response.status == 200) {

					if (response.data.success) {
						this.events = [];
						this.events = response.data.data;
						
						this.getMonthArr(this.year, this.month_num);
					} else {
						this.events = [];
						this.getMonthArr(this.year, this.month_num);
					}
				} else {
					console.log(response.data.success)
				}
			});


	   }

  	},
  	components:{
    	Month
  	},
}
</script>


<style>

</style>