export default class CalendarDate {

	constructor(year, month) {
		if (year !== undefined) {
			this.date = new Date(year, month);
		} else {
			this.date = new Date();
		}
	    
	}

	getNameMonth (key) {
		var dataMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		return dataMonths[key];
	}

	getFullYear () {
		return this.date.getFullYear();
	}

	getMonth () {
		return this.date.getMonth();
	}

	setDate (day) {
		this.date.setDate(day);
	}

	getDate () {
		return this.date.getDate();
	}

	getDay () {
		return this.date.getDay();
	}
}