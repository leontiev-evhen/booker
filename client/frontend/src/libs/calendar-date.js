export default class CalendarDate {

	constructor(year, month, days) {
		if (year !== undefined) {
			this.date = new Date(year, month, days);
		} else {
			this.date = new Date();
		}
	    
	}

	getNameMonth (key) {
		var dataMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		console.log(key)
		return dataMonths[key];
	}

	getFullYear () {
		return this.date.getFullYear();
	}

	getMonth () {
		return this.date.getMonth();
	}

	getDate () {
		return this.date.getDate();
	}

	getDay () {
		return this.date.getDay();
	}

	// последний день месяца
	getLastDayMonth (year, month) {
		return new Date(year, month + 1, 0).getDate();
	}

	// день недели последнего дня месяца
	getLastDayWeekMonth (days) {
		return new Date(this.date.getFullYear(),this.date.getMonth(), days).getDay();
	}

	// день недели первого дня месяца
	getFirstDayWeekMonth (num = 1) {
		return new Date(this.date.getFullYear(),this.date.getMonth(),1).getDay() + num;
	}

}