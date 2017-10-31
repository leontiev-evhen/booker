<template>
   		<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">Book shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="/">Home
                <span class="sr-only">(current)</span>
				<i class="fa fa-home" aria-hidden="true"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/about">About <i class="fa fa-info-circle" aria-hidden="true"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/cart">Cart <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
            </li>
          </ul>
		      <auth-form @login="login" @logout="logout"></auth-form>
        </div>
		
      </div>
    </nav>
</template>

<script>
import AuthForm from './AuthForm.vue'
export default {
  name: 'TopMenu',
  components: {
	   AuthForm
  },
  methods: {
      login: function() {
        this.$emit('login')
      },
      logout: function() {
          this.$emit('logout')
      }
  }
}
</script>

<style>

</style>