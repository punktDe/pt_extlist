jQuery(function($) {
	var datePickerOptions = {
		onSelect: submitFilterValue,
		changeMonth: true,
		changeYear: true
	};

	function submitFilterValue(date, datePicker) {
		var dateElements = date.split('.');
		var selectedDate = new Date(dateElements[2], dateElements[1] - 1, dateElements[0]);
		var startTimestamp = selectedDate.getTime() / 1000;
		var endTimestamp = startTimestamp + 86399;

		$("#datePickerFilter").val(startTimestamp + ',' + endTimestamp);

		form = "tx-ptextlist-filterbox-form-###filterBoxIdentifier###";
		document.forms[form].submit();
	}

	$("#datepicker").datepicker(datePickerOptions);

});
