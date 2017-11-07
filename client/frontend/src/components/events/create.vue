<template>
	<div>
		<h3 class="my-4"><i class="fa fa-list-alt" aria-hidden="true"></i> Create Event (Room {{$route.params.id}})</h3>
		<div class="create col-md-6">
			<form @submit.prevent="create">
				<p v-if="error" class="is-danger">{{error}}</p>
				<div class="form-group">
					<label for="text">1. Booked for:</label>
					<select v-model="id_user" class="form-control" v-if="this.$store.is_admin">
						<option v-for="user in users" :value="user.id">{{user.name}}</option>
					</select> 
					<div v-else>
						<input v-model="id_user" type="hidden" class="form-control" :value="this.$parent.$parent.user.id">
						<p>{{this.$parent.$parent.user.name}}</p>
					</div>
					
				</div>
				<div class="form-group">
					<label for="text">2. I would like to book this meeting:</label>
					<div>
						<select v-model="month" class="date_form">
							<option v-for="(item, key, index) in getMonths" :value="item.id" :selected="item.id == month">{{item.name}}</option>
						</select>
						<select v-model="day" class="date_form">
							<option v-for="item in getDays" :value="item" :selected="item == day && month == DATE.getMonth()">{{item}}</option>
						</select>
						<select v-model="year" class="date_form">
							<option v-for="item in getYears" :value="item" :selected="item == year">{{item}}</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="text">3. Choose time:</label>
					<div>
						<select v-model="hour_start" class="date_form">
							<option v-for="(item, key) in time_start" :value="item.key">{{item.value}}</option>
						</select>
						<select v-model="min_start" class="date_form">
							<option value="0" selected>00</option>
							<option  v-if="hour_start != 20" value="30">30</option>
						</select>
						<select v-model="ampm_start" @change="getTimeStart" class="date_form">
							<option>AM</option>
							<option>PM</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div>
						<select v-model="hour_end" class="date_form">
							<option v-for="(item, key) in time_end" :value="item.key">{{item.value}}</option>
						</select>
						<select v-model="min_end" class="date_form">
							<option value="0" selected>00</option>
							<option v-if="hour_end != 20" value="30">30</option>
						</select>
						<select v-model="ampm_end" @change="getTimeEnd" class="date_form">
							<option>AM</option>
							<option>PM</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="text">4. Description:</label>
					<textarea class="form-control" rows="5" id="comment" v-model="description"></textarea>
				</div>
				<div class="form-group">
					<label for="text">5. Is this going to be a recurring event?</label>
					<p>
						<label><input type="radio" name="optradio" @click="showRecurring" checked="checked">No</label>
					</p>
					<p>
						<label><input type="radio" name="optradio" @click="showRecurring">Yes</label>
					</p>
				</div>
				<div class="form-group" v-if="recurring">
					<label for="text">6. </label>
					<p>
						<label><input type="radio" name="time_recurring" @click="countRepeat('weekly')" checked>weekly</label> 
					</p>
					<p>
						<label><input type="radio" name="time_recurring" @click="countRepeat('bi-weekly')">bi-weekly</label>
					</p>
					<p>
						<label><input type="radio" name="time_recurring" @click="countRepeat('monthly')">monthly</label>
					</p>
					<div>
						<label>Duration</label>
						<p><small>weekly: max 4, bi-weekly: max 2</small></p>
						<select v-model="repeat" class="date_form">
							<option v-for="item in count_repeat" :value="item" :selected="item == 1">{{item}}</option>
						</select>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">Create</button>
			</form>
		</div>
	</div>
</template>


<script>
import CalendareDate from '../../libs/calendar-date'
export default {
  	name: 'create',
 	 data() {
		return {
			DATE: new CalendareDate(),
      		years: ['2017', '2018', '2019'],
			months: [],
			id_user: this.$parent.$parent.user.id,
			year: 0,
			month: 0,
			day: 0,
			count_days: [],
			hour_start: 0,
			min_start: 0,
			ampm_start: 'AM',
			hour_end: 0,
			min_end: 0,
			ampm_end: 'AM',
			am_time: [
				{key: 8, value: 8},
				{key: 9, value: 9},
				{key: 10, value: 10},
				{key: 11, value: 11}
			],
			pm_time: [
				{key: 12, value: 12},
				{key: 13, value: 1},
				{key: 14, value: 2},
				{key: 15, value: 3},
				{key: 16, value: 4},
				{key: 17, value: 5},
				{key: 18, value: 6},
				{key: 19, value: 7},
				{key: 20, value: 8}
			],
			time_start: [
				{key: 8, value: 8},
				{key: 9, value: 9}, 
				{key: 10, value:10}, 
				{key: 11, value: 11}, 
			],
			time_end: [
				{key: 8, value: 8},
				{key: 9, value: 9}, 
				{key: 10, value:10}, 
				{key: 11, value: 11}, 
			],
			users: [],
			recurring: false,
			time_recurring: 'weekly',
			repeat: 1,
			count_repeat: 4,
			time_data_start: 0,
			time_data_end: 0,
			description: '',
			error: '',
		}
  	},
 	methods: {
 		create: function() {
 			if (this.validator()) {
 				let self = this;
		
				let config = {
					headers: {
						'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
					}
				}
			
				this.axios.post(this.$parent.$parent.AJAX_URL + '/booker/client/api/events', {
						time_start: +this.time_data_start,
						time_end: +this.time_data_end,
						id_user: +this.id_user,
						id_room: +this.$route.params.id,
						description: this.description,
						recurring: (this.recurring) ? 1:0,
						time_recurring: this.time_recurring,
						repeat: +this.repeat,
						parent_id: 0
					}, config)  
					.then((response) => {

						if (response.status == 200) {
							if (!response.data.success) {
								self.$swal(
									'Warning!',
    								response.data.message,
    								'warning'
    							);
							} else {
								self.$swal(
									'Success!',
    								response.data.message,
    								'success'
    							);
								self.$router.push('/');
							}
						} else {
							console.log(response.data.message)
						}
					})
 			}
 		},
 		validator: function () {
			

 			this.time_data_start = this.DATE.toTimestamp((this.month + 1 )+"/" + this.day +"/"+this.year + " " + this.hour_start + ":" + this.min_start);

 			this.time_data_end = this.DATE.toTimestamp((this.month + 1 )+"/" + this.day +"/"+this.year + " " + this.hour_end + ":" + this.min_end);

 			let current_time = this.DATE.toTimestamp((this.DATE.getMonth() + 1 )+"/" + this.DATE.getDate() +"/"+this.DATE.getFullYear() + " " + this.DATE.getHours() + ":" + this.DATE.getMinutes());
		

 			if (this.recurring) {
 				switch (this.time_recurring) {
 					case 'weekly':
 						if (this.repeat > 4 || this.repeat <= 0) {
 							this.error = 'recurring event weekly in not valid, should be max 4';
 							this.$swal(
								'Warning!',
								'recurring event weekly in not valid, should be max 4',
								'warning'
							);
							return false;
 						}
 						break;
 					case 'bi-weekly':
 						if (this.repeat > 2 || this.repeat <= 0) {
 							this.error = 'recurring event bi-weekly in not valid, should be max 2';
 							this.$swal(
								'Warning!',
								'recurring event bi-weekly in not valid, should be max 2',
								'warning'
							);
							return false;
 						}
 						break;
 					case 'monthly':
 						if (this.repeat > 1 || this.repeat <= 0) {
 							this.error = 'recurring event monthly in not valid, should be max 1';
 								this.$swal(
									'Warning!',
									'recurring event monthly in not valid, should be max 1',
									'warning'
								);
							return false;
 						}
 						break;
 				}
 			}

 			if (this.time_data_start >=  this.time_data_end) {
				this.error = 'Time is not valid';
				this.$swal(
						'Warning!',
						'Time is not valid',
						'warning'
					);
				return false;
 			}

 			if (this.time_data_start < current_time) {
				this.error = 'Time params are not valid';
				this.$swal(
						'Warning!',
						'Time params are not valid',
						'warning'
					);
				return false;
 			}

 			this.error = '';
 			return true;
 		},
 		countRepeat: function (item) {
 			switch (item) {
 				case 'weekly':
 					this.time_recurring = 'weekly';
 					this.count_repeat = 4;
 					break;
 				case 'bi-weekly':
 					this.time_recurring = 'bi-weekly';
 					this.count_repeat = 2;
 					break;
 				case 'monthly':
 					this.time_recurring = 'monthly';
 					this.count_repeat = 1;
 					break;
 			}
 	
 		},
 		showRecurring: function () {
 			this.recurring = !this.recurring;
 		},
		getTimeStart: function () {
			if (this.ampm_start == 'AM') {
				this.hour_start = 8;
				this.time_start = this.am_time;
			} else {
				this.hour_start = 12;
				if (localStorage['time_format'] == 24) {
					let arr_time = [];
					for (let i in this.pm_time) {
						if (this.pm_time[i].value < 12) {
							arr_time.push({key: this.pm_time[i].key, value: this.pm_time[i].value + 12});
						} else {
							arr_time.push(this.pm_time[i]);
						}
					}
					this.time_start = arr_time;
				} else {
					this.time_start = this.pm_time;
				}
			}
		},
		getTimeEnd: function () {
			if (this.ampm_end == 'AM') {
				this.hour_end = 8;
				this.time_end = this.am_time;
			} else {
				this.hour_end = 12;
				if (localStorage['time_format'] == 24) {
					let arr_time = [];
					for (let i in this.pm_time) {
						if (this.pm_time[i].value < 12) {
							arr_time.push({key: this.pm_time[i].key, value: this.pm_time[i].value + 12});
						} else {
							arr_time.push(this.pm_time[i]);
						}
					}
					this.time_end = arr_time;
				} else {
					this.time_end = this.pm_time;
				}
			}
		},
		
		getAllDays: function () {
			
			let days_arr = this.DATE.getLastDayMonth(this.year, this.month);
			this.count_days = [];
			for (let i = 1; i <= days_arr; i++) {
			
				if (this.month == this.DATE.getMonth() && this.year == this.DATE.getFullYear()) {
					if (i >= this.DATE.getDate()) {
						this.count_days.push(i);
					}
				} else {
					this.count_days.push(i);
				}
			}

			return this.count_days;
		},
		getAllMonths: function() {
			let self = this;
			let months_arr = this.DATE.getNameMonths();
			this.months = [];
			months_arr.forEach(function(item, i, arr) {
				var obj = {id: i, name: item};
				if (self.year == self.DATE.getFullYear()) {
					if (i >= self.DATE.getMonth()) {
						self.months.push(obj);
					}
				} else {
					self.months.push(obj);
				}
			});
			return this.months;
		}
  	},
  	created() {
		this.year = this.DATE.getFullYear();
		this.month = this.DATE.getMonth();
		this.day = this.DATE.getDate();
		this.hour_start = 8;
		this.hour_end = 8;
		
	
		
		//this.getCountDays();
		
		if (this.$store.is_admin) {
			this.axios.get(this.$parent.$parent.AJAX_URL + '/booker/client/api/users').then((response) => {

			if (response.status == 200) {
				if (response.data.success) {
					this.users = response.data.data
				} else {
					this.error = response.data.message
				}
			} else {
				console.log(response.data.message)
			}
			})
		}
  		
  	},
	computed: {
		getDays: function () {
			return this.getAllDays();
		},
		getMonths: function () {
			return this.getAllMonths();
		},
		getYears: function () {
			return this.years;
		},
	}
}
</script>


<style>

</style>
