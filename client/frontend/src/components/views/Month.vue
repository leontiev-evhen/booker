<template>
  <div class="fc fc-ltr">
      <div class="fc-content" style="position: relative;">
          <div class="fc-view fc-view-month fc-grid" style="position:relative" unselectable="on">
            <table class="fc-border-separate">
                  <thead>
                    <tr class="fc-first fc-last">
                      <th v-if="format == 'ua'" v-for="day in daysTextRu">{{day}}</th>
                      <th v-if="format == 'usa'" v-for="day in daysTextUs">{{day}}</th>
                    </tr>
                  </thead>
               <tbody>
                  <tr v-for="week in months">
                
                     <td  v-for="day in week" class="fc-mon fc-widget-content fc-past" :class="{ 'fc-day': day.num}">
						            <day :day="day"></day> 
                     </td>
                  </tr>
               </tbody>
            </table>
          </div>
        </div>
  </div>
</template>

<script>

import Day from './Day'
export default {
	name: 'month',
	data () {
		return {
			daysTextUs:['Sn', 'Mn', 'Tu', 'We', 'Th', 'Fr', 'St'],
			daysTextRu:['Mn', 'Tu', 'We', 'Th', 'Fr', 'St', 'Sn']
		}
	},
	props:['months', 'format'],
	components: {
		Day
	}
}  
</script>


<style>

</style>