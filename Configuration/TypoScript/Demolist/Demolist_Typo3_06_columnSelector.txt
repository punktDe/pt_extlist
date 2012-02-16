####################################################
# This template configures a demolist to the use of the selectable columns
# with pt_extlist
#
#
# @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################


plugin.tx_ptextlist.settings {

	listConfig.demolist_typo3_06_columnSelector < plugin.tx_ptextlist.settings.listConfig.demolist
	listConfig.demolist_typo3_06_columnSelector {

		columns.11.isVisible = 0
		columns.20.isVisible = 0

		columnSelector.enabled = 1

	}

	listConfig.demolist_typo3_06_columnSelector_export  < plugin.tx_ptextlist.settings.listConfig.demolist_typo3_06_columnSelector
	listConfig.demolist_typo3_06_columnSelector_export {

		columnSelector {
			enabled = 1

			hideDefaultVisibleInSelector = 1
			persistToSession = 0
			onlyShowSelectedColumns = 0

			partialPath = ColumnSelector/SelectAndDownload
		}
	}
}