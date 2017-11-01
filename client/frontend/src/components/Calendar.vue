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
										 <h2>{{month}} {{year}}</h2>
									  </span>
									</td>
			
									<td class="fc-header-right">
											<span @click="changeFormat" class="fc-button fc-button-prev fc-state-default format">
											{{format}}
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
					<button type="submit" class="btn btn-primary">Book it</button>
					<button type="submit" class="btn btn-primary">Employee list</button>
				</center>
			</div>
		</div>
      
    </div>
</template>

<script>
import CalendarDate from '../libs/calendar-date'
import Month from './Month'
export default {
  	name: 'calend',
  	data () {
    	return {
	      month:'',
	      dayArr:[],
	      date: new CalendarDate(),
	      year: '',
	      monthNumber: '',
	      format:'RU'
    	}
  	},
  	created:function() {
  		this.year = this.date.getFullYear();
  		this.monthNumber = this.date.getMonth();
    	this.getMonthArr(this.year, this.monthNumber)
  	},
  	components:{
    	Month
  	},
  methods: {
    	getMonthArr(year, month, format = 'ru' ) {

	      var date = new CalendarDate(year, month).date;
	      this.month = this.date.getNameMonth(month);
	      var clearDay = 0; //количество дней, которые нужно пропустить
	      this.dayArr[0] = [];

	      for (var i = 0; i < this.getDay(date,format); i++) {
	          this.dayArr[0].push({});
	          clearDay ++;
	      }

	      // ячейки календаря с датами
	      var i = 0;
	      while (date.getMonth() == month) {
	          this.dayArr[i].push({data: date.getDate()})

	        	if (this.getDay(date,format) % 7 == 6) { // вс, последний день - перевод строки
	          	i ++
	          	this.dayArr[i] = [];
	        	}

	        date.setDate(date.getDate() + 1);
	      }
	      return this.dayArr;
      
    	},

	   getDay(date, format) { // получить номер дня недели, от 0(пн) до 6(вс)
         var day = date.getDay();
         if(format == 'ru') {
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
	      if(this.format == 'RU'){
	        this.format = 'US'
	        this.dayArr = this.getMonthArr(this.year, this.monthNumber,'en')
	      }else{
	        this.format = 'RU'
	        this.dayArr = this.getMonthArr(this.year, this.monthNumber,'ru')
	      }
	   },

  	}
}
</script>


<style>

</style>