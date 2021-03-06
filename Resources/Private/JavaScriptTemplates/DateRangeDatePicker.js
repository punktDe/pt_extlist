if ('undefined' !== typeof TYPO3) {
    var $ = $ || TYPO3.jQuery;
    var jQuery = jQuery || TYPO3.jQuery;
}

$(function () {
    window.setTimeout(function () {
        var baseId = "#typo3-ptextlist-filter-daterangedatepicker-" + "###filterIdentifier###".toLowerCase();
        var altFormat = "yy-mm-dd";
        var datePickerFromOptions = {
            altFormat: altFormat,
            altField: baseId + "-daterangefrom"
        };
        var datePickerToOptions = {
            altFormat: altFormat,
            altField: baseId + "-daterangeto"
        };

        $(baseId + "-from").change(function () {
            if (!$(this).val()) $(baseId + "-daterangefrom").val('');
        });
        $(baseId + "-to").change(function () {
            if (!$(this).val()) $(baseId + "-daterangeto").val('');
        });

        $(baseId + "-from").datepicker(datePickerFromOptions);
        $(baseId + "-to").datepicker(datePickerToOptions);
    }, 1500);
});
