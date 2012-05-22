jQuery(function($) {
	var altFormat = "yymmdd";
	var datePickerFromOptions = {
		altFormat: altFormat,
		altField: "#typo3-ptextlist-filter-daterangedatepicker-daterangefrom"
	};
	var datePickerToOptions = {
		altFormat: altFormat,
		altField: "#typo3-ptextlist-filter-daterangedatepicker-daterangeto"
	};

	$("#typo3-ptextlist-filter-daterangedatepicker-from").change(function(){
		if (!$(this).val()) $("#typo3-ptextlist-filter-daterangedatepicker-daterangefrom").val('');
	});
	$("#typo3-ptextlist-filter-daterangedatepicker-to").change(function(){
		if (!$(this).val()) $("#typo3-ptextlist-filter-daterangedatepicker-daterangeto").val('');
	});

	$('#typo3-ptextlist-filter-daterangedatepicker-from').datepicker(datePickerFromOptions);
	$('#typo3-ptextlist-filter-daterangedatepicker-to').datepicker(datePickerToOptions);
});