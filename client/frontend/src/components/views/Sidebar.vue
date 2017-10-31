<template>
  <div class="home">
   		<h3 class="my-4">Authors</h3>
      	<div class="list-group">
        	<a :href="'/authors/' + author.id" v-for="author in authors" class="list-group-item">{{author.name}}</a>
      	</div>
      	<h3 class="my-4">Genres</h3>
      	<div class="list-group">
        	<a :href="'/genres/' + genre.id" v-for="genre in genres" class="list-group-item">{{genre.name}}</a>
      	</div>
        </div>
        <!-- /.col-lg-3 -->
    </div>
</template>


<script>

export default {
  name: 'sidebar',
  data () {
    return {
      authors: '',
      genres: ''
    }
  },
  created() { 
    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/authors').then((response) => {

          if (response.status == 200) {
            if (response.data.status) {
              this.authors = response.data.data
            } else {
              console.log(response.data.message)
            }
          } else {
            console.log(response.data.message)
          }
    })

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/genres').then((response) => {

          if (response.status == 200) {
            if (response.data.status) {
              this.genres = response.data.data
            } else {
              console.log(response.data.message)
            }
          } else {
            console.log(response.data.message)
          }
    })

  },
}
</script>

<style>

</style>
