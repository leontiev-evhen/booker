export default class CalendarDate {

	constructor(year, month) {
		this.date = new Date(); 
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

	getHours () {
		return this.date.getHours();
	}

	getMinutes () {
		return this.date.getMinutes();
	}

	getSeconds() {
		return this.date.getTime() / 1000;
	}

	toTimestamp(strDate){
		let datum = Date.parse(strDate);
		return datum / 1000;
	}

	getLastDayMonth (year, month) {
		return new Date(year, month + 1, 0).getDate();;
	}

}