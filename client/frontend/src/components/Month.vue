<template>
  <div class="fc fc-ltr">
      <div class="fc-content" style="position: relative;">
          <div class="fc-view fc-view-month fc-grid" style="position:relative" unselectable="on">
            <table class="fc-border-separate">
                  <thead>
                    <tr class="fc-first fc-last">
                      <th v-if="format == 'RU'" v-for="day in daysTextRu">{{day}}</th>
                      <th v-if="format == 'US'" v-for="day in daysTextUs">{{day}}</th>
                    </tr>
                  </thead>
               <tbody>
                  <tr v-for="week in monthArr">
                
                     <td  v-for="day in week" class="fc-day fc-mon fc-widget-content fc-past">
                     <div class="cell">
                        <div class="fc-day-number" v-bind:class="{ today: current_day }">

                            <day v-bind:day="day"></day> 

                        </div>
                        <div class="fc-day-content"><div>&nbsp;</div>
                        </div>
                     </div>
                  
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
      msg: 'month',
      current_day: false,
      daysTextUs:['Sn', 'Mn', 'Tu', 'We', 'Th', 'Fr', 'St'],
      daysTextRu:['Mn', 'Tu', 'We', 'Th', 'Fr', 'St', 'Sn']
    }
  },
  props:['monthArr','format'],
  components:{
    Day
  }
}  
</script>


<style>

</style>