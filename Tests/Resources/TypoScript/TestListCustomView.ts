####################################################
# Tests if a custom defined view is used
#
# Use as a list plugin
#
# @author Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

plugin.tx_ptextlist.settings.listConfig.testListPartialResolver < plugin.tx_ptextlist.settings.listConfig.testListBase
plugin.tx_ptextlist.settings.listConfig.testListPartialResolver {
    controller.List.list.view = Tx_PtExtlist_Tests_View_TestView
}