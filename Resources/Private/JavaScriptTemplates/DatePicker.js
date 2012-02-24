jQuery(function($) {
	var options = ###options###;
	var filterValue = "###filterValue###";

	var defaultDate = new Date();
	if (filterValue) {
		defaultDate = new Date(filterValue.split('-')[0], filterValue.split('-')[1] - 1, filterValue.split('-')[2]);
	}
	var datePickerOptions = {
		altFormat: "yymmdd",
		altField: "#datePickerFilter",
		defaultDate: defaultDate,
		onSelect: submitFilterValue,
		beforeShowDay: evaluateTimeSpans
	};

	$.extend(datePickerOptions, $.datepicker.regional['###regional###']);
	$.extend(datePickerOptions, ###datePickerOptions###);

	function submitFilterValue(date, datePicker) {
		var form = 'tx-ptextlist-filterbox-form-###filterBoxIdentifier###';
		document.forms[form].submit();
	}

	function evaluateTimeSpans(date) {
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		var dateInAltFormat = $.datepicker.formatDate('yymmdd', date);

		for (var i = 0; i < options.timeSpans.length; i++) {
			if (options.timeSpans[i].start <= dateInAltFormat && options.timeSpans[i].end >= dateInAltFormat) {
				return [true, 'typo3-ptextlist-filter-eventdate'];
			}
		}
		return [false, ''];
	}

	$('#typo3-ptextlist-filter-datepicker').datepicker(datePickerOptions);
});