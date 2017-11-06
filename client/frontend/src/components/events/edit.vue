<template>
  	<div class="edit">
	  	<h3 class="my-4"><i class="fa fa-list-alt" aria-hidden="true"></i> Edit event</h3>
		<form @submit.prevent="edit">
			<p v-if="error" class="is-danger">{{error}}</p>
			<div class="form-group">
				<label for="text">When:</label>
				<div>
					<select v-model="hour_start" class="date_form">
						<option v-for="(item, key) in time" :value="item.key" >{{item.value}}</option>
					</select>
					<select v-model="min_start" class="date_form">
						<option value="0">00</option>
						<option  v-if="hour_start != 20" value="30">30</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div>
					<select v-model="hour_end" class="date_form">
						<option v-for="(item, key) in time" :value="item.key">{{item.value}}</option>
					</select>
					<select v-model="min_end" class="date_form">
						<option value="0">00</option>
						<option v-if="hour_end != 20" value="30">30</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="text">Description:</label>
				<textarea class="form-control" rows="5" id="comment" v-model="description" v-default-value="event.description"></textarea>
			</div>
			<div class="form-group">
				<label for="text">Who:</label>
				<select v-model="id_user" class="form-control" v-if="this.$store.is_admin">
					<option v-for="user in users" :value="user.id">{{user.name}}</option>
				</select> 
				<div v-else>
					<input v-model="id_user" type="hidden" class="form-control" :value="this.$parent.$parent.user.id">
					<p>{{this.$parent.$parent.user.name}}</p>
				</div>
			</div>
			<div class="form-group" v-if="event.child">
				<label><input type="checkbox" name="time_recurring" v-model="recurring">Apply to all occurrences</label> 
			</div>
			<div class="form-group" v-if="event.child">
				<p>Created: {{event.create_at}}</p>
			</div>
			<button type="submit" class="btn btn-primary">Update</button>
		</form>
		<button type="submit" class="btn btn-danger">Delete</button>
	</div>
</template>


<script>
import CalendarDate from '../../libs/calendar-date'
export default {
  	name: 'edit',
 	 data() {
		return {
			DATE: new CalendarDate(),
			event: '',
			users: '',
			id_user: '',
			id_room: '',
			description: '',
			time_start: '',
			time_end: '',
			recurring: false,
			time: [
				{key: 8, value: 8},
				{key: 9, value: 9}, 
				{key: 10, value:10}, 
				{key: 11, value: 11}, 
				{key: 12, value: 12}, 
				{key: 13, value: 1}, 
				{key: 14, value: 2}, 
				{key: 15, value: 3}, 
				{key: 16, value: 4}, 
				{key: 17, value: 5}, 
				{key: 18, value: 6}, 
				{key: 19, value: 7}, 
				{key: 20, value: 8}, 
			],
			hour_start: 0,
			hour_end: 0,
			min_start: 0,
			min_end: 0,
			year: 0,
			month: 0,
			day: 0,
			time_data_start: 0,
			time_data_end: 0,
			error: ''
		}
  	},
 	methods: {
	
		checkTimeFormat: function () {
			
			if (localStorage['time_format'] == 24) {
				let arr_time = [];
				for (let i in this.time) {
					if (this.time[i].key >= 13) {
						arr_time.push({key: this.time[i].key, value: this.time[i].value + 12});
					} else {
						arr_time.push(this.time[i]);
					}
				}
				this.time = arr_time;
			} else {
				console.log(123)
				this.time = this.time;
			}
		},
		validator: function () {
			
 			this.time_data_start = this.DATE.toTimestamp((this.month + 1 )+"/" + this.day +"/"+this.year + " " + this.hour_start + ":" + this.min_start);

 			this.time_data_end = this.DATE.toTimestamp((this.month + 1 )+"/" + this.day +"/"+this.year + " " + this.hour_end + ":" + this.min_end);


 			if (this.time_data_start >=  this.time_data_end) {
				this.error = 'Time is not valid';
				this.$swal(
						'Warning!',
						'Time is not valid',
						'warning'
					);
				return false;
 			}

 			this.error = '';
 			return true;
 		},
 		edit: function() {

			if (this.validator()) {
				let config = {
					headers: {
						'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
					}
			}
		
				this.axios.put(this.$parent.$parent.AJAX_URL + '/booker/client/api/events/id/' + this.$route.params.id, {
						time_start: +this.time_data_start,
						time_end: +this.time_data_end,
						id_user: +this.id_user,
						id_room: +this.id_room,
						description: (this.description) ? this.description : this.event.description,
						recurring: (this.recurring) ? 1:0
					}, config)  
					.then((response) => {

						if (response.status == 200) {
							if (!response.data.success) {
								console.log(response.data.message)
							} else {
								this.$swal(
									'Success!',
    								response.data.message,
    								'success'
    							).then(function () {
    								window.close();
    							});								
							}
						} else {
							console.log(response.data.message)
						}
					})
			}
		
 		}
  	},
    created() {

    	this.checkTimeFormat();

    	let self = this;
		this.axios.get(this.$parent.$parent.AJAX_URL + '/booker/client/api/events/id/' + this.$route.params.id)  
	    	.then(function (response) {
	    		console.log(response.status)
			  	if (response.status == 200) {
		            if (response.data.success) {
		            	self.event = response.data.data
		            	self.id_user = self.event.id_user;

		            	self.hour_start = new Date(self.event.time_start * 1000).getHours();
		            	self.min_start = new Date(self.event.time_start * 1000).getMinutes();
		            	self.hour_end = new Date(self.event.time_end * 1000).getHours();
		                self.min_end = new Date(self.event.time_end * 1000).getMinutes();

		                self.year = new Date(self.event.time_end * 1000).getFullYear();
		                self.month = new Date(self.event.time_end * 1000).getMonth();
		                self.day = new Date(self.event.time_end * 1000).getDate();

		                self.id_room = self.event.id_room;

		            } else {
		            	console.log(response.data.message)
		            }
		      	} 
		}).catch(e => {
      		self.$router.push('/');
    	})

	    //if (this.$store.is_admin) {
			this.axios.get(this.$parent.$parent.AJAX_URL + '/booker/client/api/users').then((response) => {

			if (response.status == 200) {
				if (response.data.success) {
					self.users = response.data.data
				} else {
					self.error = response.data.message
				}
			} else {
				console.log(response.data.message)
			}
			})
		//}

		
  	}
}
</script>


<style>

</style>
