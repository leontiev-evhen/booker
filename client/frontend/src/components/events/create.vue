<template>
	<div>
		<h3 class="my-4"><i class="fa fa-list-alt" aria-hidden="true"></i> Create Event</h3>
		<div class="create col-md-6">
			<form @submit.prevent="create">
				<p v-if="error" class="is-danger">{{error}}</p>
				<div class="form-group">
					<label for="text">1. Booked for:</label>
					<select v-model="id_user" class="form-control" v-if="this.$store.is_admin">
						<option v-for="user in users" :value="user.id">{{user.name}}</option>
					</select>
					<input v-else type="text" class="form-control" :value="this.$parent.$parent.user.name" disabled>
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
							<option v-for="num in time_start" :value="num">{{num}}</option>
						</select>
						<select v-model="min_start" class="date_form">
							<option value="0" selected>00</option>
							<option value="30">30</option>
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
							<option v-for="num in time_end" :value="num">{{num}}</option>
						</select>
						<select v-model="min_end" class="date_form">
							<option value="0" selected>00</option>
							<option value="30">30</option>
						</select>
						<select v-model="ampm_end" @change="getTimeEnd" class="date_form">
							<option>AM</option>
							<option>PM</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="text">4. Description:</label>
					<textarea class="form-control" rows="5" id="comment"></textarea>
				</div>
				<div class="form-group">
					<label for="text">5. Is this going to be a recurring event?</label>
					<p>
						<label><input type="radio" name="optradio">No</label>
					</p>
					<p>
						<label><input type="radio" name="optradio">Yes</label>
					</p>
				</div>
				<div class="form-group">
					<label for="text">6. </label>
					<p>
						<label><input type="radio" name="optradio">weekly</label>
					</p>
					<p>
						<label><input type="radio" name="optradio">bi-weekly</label>
					</p>
					<p>
						<label><input type="radio" name="optradio">monthly</label>
					</p>
					<label>Duration</label>
					<input type="text" class="form-control" id="usr">
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
			id_user: 0,
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
			am_time: [8, 9, 10, 11],
			pm_time: [12, 1, 2, 3, 4, 5, 6, 7, 8],
			time_start: [8, 9, 10, 11],
			time_end: [8, 9, 10, 11],
			users: [],
			error: '',
			id_role: ''
		}
  	},
 	methods: {
 		/*create: function() {
 			let self = this
			this.$validator.validateAll().then((result) => {
		        if (result) {
					let config = {
						headers: {
							'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
						}
					}
				
					this.axios.post(this.$parent.$parent.AJAX_URL + '/booker/client/api/events', {
							name: this.name,
							email: this.email,
							password: btoa(this.password),
							discaunt: (this.discaunt) ? +this.discaunt : 0,
							status: 1,
						}, config)  
						.then((response) => {

							if (response.status == 200) {
								if (!response.data.success) {
									self.error = response.data.message
								} else {
									location.href = '/admin/users'
								}
							} else {
								console.log(response.data.message)
							}
						})
				}
     		});
 		}*/
		getTime: function (ampm, time) {
			if (this.ampm_start == 'AM') {
				this.time_start = this.am_time;
			} else {
				if (this.$store.time_format == 24) {
					let arr_time = [];
					for (let i in this.pm_time) {
						if (this.pm_time[i] < 12) {
							arr_time.push(this.pm_time[i] + 12);
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
		getTimeStart: function () {
			if (this.ampm_start == 'AM') {
				this.time_start = this.am_time;
			} else {
				if (this.$store.time_format == 24) {
					let arr_time = [];
					for (let i in this.pm_time) {
						if (this.pm_time[i] < 12) {
							arr_time.push(this.pm_time[i] + 12);
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
				this.time_end = this.am_time;
			} else {
				if (this.$store.time_format == 24) {
					let arr_time = [];
					for (let i in this.pm_time) {
						if (this.pm_time[i] < 12) {
							arr_time.push(this.pm_time[i] + 12);
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

		
	
		
		//this.getCountDays();
		
		
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
