/**
 * jQuery UI Date Picker
 *
 * This code is based on on:
 *  - jquery-1.3.2.min.js
 *  - jquery-ui-1.7.2.custom[.min].js
 *
 * Remark:
 * For LTR languages the upper left corner of the calendar widget is
 * aligned to the left edge of its respective input field. For RTL
 * languages (e.g. Persian) the upper right corner of the widget is
 * aligned to the right edge of the input field. In this case the
 * widget might exceed the browser window, if your input field is
 * aligned on the left side.
 *
 * @author Joachim Mathes
 * @since  2009-07-15
 */

$(function(){
    // define config options
    var pickerOpts = {
      showOn: "button",
      buttonImage: "../jqueryui/development-bundle/demos/datepicker/images/calendar.gif",
      buttonImageOnly: "true"
    };

    // set date picker defaults
    // todo get date picker regional settings from typo3 settings
    $.datepicker.setDefaults($.extend({showMonthAfterYear: false},
                                      $.datepicker.regional[""],
                                      pickerOpts));

    // create the date picker for the "from" field
    $("#date_from").datepicker($.datepicker.regional["de"]);
    $("#locale").change(function() {
                          $("#date_from").datepicker("option", $.extend({showMonthAfterYear: false},
                                                                   $.datepicker.regional[$(this).val()]));
                        });
    // create the date picker for the "to" field
    $("#date_to").datepicker($.datepicker.regional["de"]);
    $("#locale").change(function() {
                          $("#date_to").datepicker("option", $.extend({showMonthAfterYear: false},
                                                                   $.datepicker.regional[$(this).val()]));
                        });
 });