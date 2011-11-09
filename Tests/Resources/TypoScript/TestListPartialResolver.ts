####################################################
# Tests if partials can be resolved
#
# Use as a list plugin
#
# @author Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

plugin.tx_ptextlist.settings.listConfig.testListPartialResolver < plugin.tx_ptextlist.settings.listConfig.testListBase
plugin.tx_ptextlist.settings.listConfig.testListPartialResolver {
    controller.List.list.template = EXT:pt_extlist/Tests/Resources/Templates/PartialResolverTest.html
}