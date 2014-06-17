************
Changelog
************

Before updating your version of pt_extlist, you should read this to get an impression on what has changed. Make sure to follow advices on which steps are neccessary to make pt_extlist run again after updating.

Changes

**0.4.0**

	ADD: Template for filterbox can now be overwritten in Flexform

	ADD: Export is refactored. Export widget now generates direct link for export, page for export list settings is no longer required.

	ADD: Excel export is implemented. Requires PHPExcel PEAR package!

	FIX: ConfigurationBuilder is no longer singleton, if Flexform matters.

	RFT: Format of documentation changed from article to book. Now we have TOC!

	FIX: Removed non-required spaces in pager template to fix CSS rendering.

	RFT: Introduced sorter to handle sorting in data backend.

	CHG: It is now possible to reset filterboxes individually. If you use your own Filterbox templates, you have to update them! Make sure to submit filterbox identifier on sending reset request.

	ADD: ListHeader viewhelper now accepts single columns / fields for sorting. If you use your own Header templates, you have to update them!

	ADD: Filterboxes can now exclude each other. If Filterbox A is configured to exclude Filter b from Filterbox B, than B.b is not respected when filtering data, if Filter B is active.

	ADD: List can now be resetted if no GPvars are send. This enables resetting a page whenever a user visits it for the first time.

	ADD: Improved Extbase data backend. Now respects sorting of columns and translates NOT criteria.

	ADD: Query for creating list data is now written to devlog.

	ADD: Added new dateSelect filter. See documentation for configuration settings.

	ADD: Added documentation on how to use pt_extlist as TypoScript widget.

	ADD: Added fulltext-filter (only works with MySQL data backend).

	ADD: Added redirect on submit for filterboxes.


