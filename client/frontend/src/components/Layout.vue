<template>
  	<div class="layout">
		<router-view></router-view>
	</div>
</template>


<script>
export default {
	name: 'layout',
  	created() {

		if (this.isJson('profile')) {
			let self = this;
			let user = JSON.parse(localStorage.getItem("profile"));

			if (user.access_token) {
				let instance = this.axios.create({
					baseURL: this.$parent.AJAX_URL
				});
				instance.defaults.headers.common['Authorization'] = user.access_token

				this.axios.get(this.$parent.AJAX_URL + '/booker/client/api/auth')  
				.then(function (response) {
				if (response.status == 200) {
					if (!response.data.success) {
						this.$parent.logout();
					}
				} else {
					console.log(response.data.message);
				}
				})
			} else {
				this.$parent.logout();
			}
		} else {
			//this.$parent.logout();
		}
		
  	},
  	methods: {
  		isJson: function (str) {
		    try {
		        JSON.parse(str);
		    } catch (e) {
		        return false;
		    }
		    return true;
		}
  	}
}
</script>


<style>
</style>
