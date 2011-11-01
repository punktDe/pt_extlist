####################################################
# This template configures a testlist
#
# @author Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

plugin.tx_ptextlist.settings {

	listConfig.testListBase {

		backendConfig < plugin.tx_ptextlist.prototype.backend.typo3
		backendConfig {
			tables (
				static_countries
			)
		}

		fields {
			name_en {
				table = static_countries
				field = cn_short_en
			}

			capital {
				table = static_countries
				field = cn_capital
			}
		}

		columns {

			10 {
				columnIdentifier = nameColumn
				label = Name
				fieldIdentifier = name_en
            }

			20 {
				label = Capital
				columnIdentifier = capital
				fieldIdentifier = capital
			}
		}
	}
}