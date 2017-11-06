export default class CalendarDate {

	constructor(year, month) {
		this.date = new Date(); 
	}
	
	getNameMonths () {
		return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
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

	formattedDate (date_start, date_end, time_format) {
		var time_start = new Date(date_start * 1000);
		var time_end = new Date(date_end * 1000);
		var hour_start;
		var hour_end;
		var min_start;
		var min_end;

		if (time_format == 12 && time_start.getHours() >= 12) {
			hour_start = time_start.getHours() - 12;
			hour_end = time_end.getHours() - 12;
		} else {
			hour_start = time_start.getHours();
			hour_end = time_end.getHours();
		}

		if (time_start.getMinutes() == 0) {
			min_start = time_start.getMinutes() + "0";
		} else {
			min_start = time_start.getMinutes();
		}

		if (time_end.getMinutes() == 0) {
			min_end = time_end.getMinutes() + "0";
		} else {
			min_end = time_end.getMinutes();
		}

		return hour_start + ':' + min_start + ' - ' + hour_end + ':' + min_end;
	}

}