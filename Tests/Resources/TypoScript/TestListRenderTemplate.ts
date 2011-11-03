####################################################
# Tests if partials can be resolved
#
# Use as a list plugin
#
# @author Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

plugin.tx_ptextlist.settings.listConfig.testListRenderTemplate < plugin.tx_ptextlist.settings.listConfig.testListBase
plugin.tx_ptextlist.settings.listConfig.testListRenderTemplate {

    columns >
    columns {
        10 {
            columnIdentifier = nameColumn
            label = Name
            fieldIdentifier = name_en
            renderTemplate = EXT:pt_extlist/Tests/Resources/Templates/RenderTemplateTest.html
        }
    }
}