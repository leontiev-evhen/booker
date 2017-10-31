<template>
  <div class="category">
  		<h3 class="my-4">{{category.name}}</h3>
        <div class="row">
            <div v-for="book in books" class="col-lg-4 col-md-6 mb-4">
              	<div class="card h-100">
	                <a :href="'/book/' + book.id"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
                  <p v-if="book.discaunt != 0" class="discount"> - <i class="fa fa-eur" aria-hidden="true"></i> {{book.discaunt}}</p>
	                <div class="card-body">
	                  	<h4 class="card-title">
	                    	<a :href="'/book/' + book.id">{{book.name}}</a>
	                  	</h4>
	                  	<h5><i class="fa fa-eur" aria-hidden="true"></i>{{book.price}}</h5>
	                  	<p class="card-text">{{book.description}}</p>
	                </div>
	                <div class="card-footer">
	                  	<small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
	                </div>
              	</div>
            </div>
        </div>
    </div>
</template>


<script>

export default {
  name: 'category',
  data () {
    return {
    	books: '',
    	category: ''
    }
  },
  created() {
  	this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/' + this.$route.params.category + '/' + this.$route.params.id).then((response) => {

          if (response.status == 200) {
            if (response.data.status) {
              this.category = response.data.data
            } else {
              console.log(response.data.message)
            }
          } else {
            console.log(response.data.message)
          }
    })

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/books/all/' + this.$route.params.category.slice(0, -1) + '/' + this.$route.params.id).then((response) => {

          if (response.status == 200) {
            if (response.data.status) {
              this.books = response.data.data

            } else {
              console.log(response.data.message)
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
