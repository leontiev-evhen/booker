<template>
  <div id="app" class="container">
    <router-view @login="login" @logout="logout"></router-view>
  </div>
</template>

<script>

export default {
    name: 'app',
    data() {
        return {
              AJAX_URL: 'http://192.168.0.15/~user11',
              //AJAX_URL: 'http://courses.site',
              BASE_URL: 'http://192.168.0.15/~user11/booker',
			  //BASE_URL: '',
              user: null
        }
    },
    methods: {
        login: function() {
          this.user = JSON.parse(localStorage.getItem("profile"));
        },
        logout: function() {
            delete localStorage["profile"];
            this.$store.is_admin = false;
            this.$router.push('/login');
        }
    },
    created() {
        this.user = JSON.parse(localStorage.getItem("profile"));
    }
}
</script>

<style>

</style>
