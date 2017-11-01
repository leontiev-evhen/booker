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
			
									<td class="fc-header-right">
											<span @click="changeFormat" class="fc-button fc-button-prev fc-state-default format">
											Format <img :src="'/static/images/' + format + '.png'" class="format_img">
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
						<month :monthArr="dayArr" :format="format"></month>
		   		</div>
		      </div>

	          	
	      </div>

	      <div class="col-md-3 right_sidebar">
	      	<center class="mt-15">
					<a href="/admin/event/create" class="btn btn-brown">
						Book it 
						<i class="fa fa-plus-circle" aria-hidden="true"></i>
					</a>
					<a href="/admin/users" class="btn btn-brown">
						Employee list
						<i class="fa fa-list" aria-hidden="true"></i>
					</a>
					<h3>List rooms</h3>
				</center>
				
			</div>
		</div>
      
    </div>
</template>

<script>
import CalendarDate from '../libs/calendar-date'
import Month from './Month'
export default {
  	name: 'home',
  	data () {
    	return {
	      month:'',
	      dayArr:[],
	      DATE: new CalendarDate(),
	      year: '',
	      monthNumber: '',
	      format:'ua'
    	}
  	},
  	created:function() {
		console.log(this.$store.admin)
  		this.year = this.DATE.getFullYear();
  		this.monthNumber = this.DATE.getMonth();
    	this.getMonthArr(this.year, this.monthNumber)
  	},
  	components:{
    	Month
  	},
	methods: {
    	getMonthArr(year, month, format = 'ua' ){
		
			  this.dayArr = [];
	
			  let date = new CalendarDate(year, month);
			  
			  this.month = this.DATE.getNameMonth(month);
			  let clearDay = 0; //количество дней, которые нужно пропустить
			  this.dayArr[0] = [];
			  for (let i = 0; i < this.getDay(date,format); i++) {
				  this.dayArr[0].push({});
				  clearDay ++
			  }
			  // ячейки календаря с датами
			  let row = 0;
			 
			  let i = 1;
			  while (date.getMonth() == month) {
				let current = false;  
				  if (
				  i == this.DATE.getDate() && 
				  this.DATE.getMonth() == date.getMonth() && 
				  this.DATE.getFullYear() == date.getFullYear()) {
						current = true;						
				  } 
				  
				  this.dayArr[row].push({data: date.getDate(), current: current})
				if (this.getDay(date,format) % 7 == 6) { // вс, последний день - перевод строки
				  row ++
				  this.dayArr[row] = []
				}
				date.setDate(date.getDate() + 1);
				i++;
			  }
			  return this.dayArr
      
		},

	   getDay(date, format) { // получить номер дня недели, от 0(пн) до 6(вс)
         var day = date.getDay();
         if(format == 'ua') {
           if (day == 0) day = 7;
           return day -1;
         }
         return day;
	   },


		changeMonth (operation) {
			this.monthNumber += operation
	      
			if (this.monthNumber < 0){
				this.monthNumber = 11
				this.year--
			}

			if (this.monthNumber > 11) {
				this.monthNumber = 0
				this.year++
			}
			
			this.dayArr = this.getMonthArr(this.year, this.monthNumber)
	   },

	   changeFormat() {
	      if(this.format == 'ua'){
	        this.format = 'usa'
	        this.dayArr = this.getMonthArr(this.year, this.monthNumber,'usa')
	      }else{
	        this.format = 'ua'
	        this.dayArr = this.getMonthArr(this.year, this.monthNumber,'ua')
	      }
	   },

  	}
}
</script>


<style>

</style>