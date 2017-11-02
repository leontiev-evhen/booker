<template>
	<div>
		<h3 class="my-4"><i class="fa fa-list-alt" aria-hidden="true"></i> Create Event</h3>
		<div class="create col-md-6">
			<form @submit.prevent="create">
				<p v-if="error" class="is-danger">{{error}}</p>
				<div class="form-group">
					<label for="text">1. Booked for:</label>
					<select v-model="id_user" class="form-control" v-if="this.$store.admin">
						<option v-for="user in users" :value="user.id">{{user.name}}</option>
					</select>
					<input v-else type="text" class="form-control" :value="this.$parent.$parent.user.name" disabled>
				</div>
				<div class="form-group">
					<label for="text">2. I would like to book this meeting:</label>
					<div>
						<select v-model="month" class="date_form">
							<option v-for="user in users" :value="user.id">{{user.name}}</option>
						</select>
						<select v-model="day" class="date_form">
							<option v-for="user in users" :value="user.id">{{user.name}}</option>
						</select>
						<select v-model="year" class="date_form">
							<option v-for="year in years" :value="year">{{year}}</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="text">3. Choose time:</label>
					<div>
						<select v-model="hour_start" class="date_form">
							<option v-for="num in 20" v-if="num >= 8" :value="num">{{num}}</option>
						</select>
						<select v-model="id_role" class="date_form">
							<option value="00">00</option>
							<option value="30">30</option>
						</select>
						<select v-model="id_role" class="date_form">
							<option>AM</option>
							<option>PM</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div>
						<select v-model="hour_end" class="date_form">
							<option v-for="num in 20" v-if="num >= 8" :value="num">{{num}}</option>
						</select>
						<select v-model="id_role" class="date_form">
							<option value="00">00</option>
							<option value="30">30</option>
						</select>
						<select v-model="id_role" class="date_form">
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
		<div class="col-md-2">
			<label for="text">Choose time format:</label>
			<select class="date_form">
				<option>12</option>
				<option>24</option>
			</select>
		</div>
	</div>
</template>


<script>

export default {
  	name: 'create',
 	 data() {
		return {
      		years: ['2017', '2018', '2019'],
			id_user: 0,
			year: 0,
			month: 0,
			day: 0,
			hour_start: 0,
			hour_end: 0,
			error: ''
		}
  	},
 	methods: {
 		create: function() {
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
 		}
  	},
  	created() {
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
}
</script>


<style>

</style>
