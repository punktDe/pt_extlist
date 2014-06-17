####################################################
# This template configures a demolist for use
# with pt_extlist
#
# @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

plugin.tx_ptextlist.settings.listConfig {

	kickstart {

		backendConfig < plugin.tx_ptextlist.prototype.backend.typo3
		backendConfig {

			tables (
				<TABLE>
			)

			#baseFromClause ()

			#baseWhereClause ()
		}

		fields {
			<FIELDIDENTIFIER> {
				table = <TABLE>
				field = <FIELD>
			}
		}

		columns {
			10 {
				columnIdentifier = <COLUMNIDENTIFIER>
				label = <THELABEL>
				fieldIdentifier = <FIELDIDENTIFIER>

				# renderObj = COA
				# renderObj {
				# }
			}
		}

		filters {
			filterbox1 {

			#	filterConfigs {
			#		10 < plugin.tx_ptextlist.prototype.filter.string
			#		10 {
			#			filterIdentifier = <FIELDIDENTIFIER>
			#			label = <FILTERLABEL>
			#			fieldIdentifier = <FIELDIDENTIFIER>
			#		}
			#	}
			}
		}
	}
}