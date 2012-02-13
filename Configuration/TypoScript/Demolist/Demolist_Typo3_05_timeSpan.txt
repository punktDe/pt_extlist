####################################################
# This template configures a demolist to show time span filter
# with pt_extlist
#
#
# @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################


plugin.tx_ptextlist.settings {

	listConfig.demolist_typo3_04_timeSpan {

		backendConfig < plugin.tx_ptextlist.prototype.backend.typo3
		backendConfig {

			baseFromClause (
			    sys_log
			    INNER join fe_users on sys_log.userid = fe_users.uid
			)
		}


		fields {

			tstamp {
				table = sys_log
				field = tstamp
			}

			readableDate {
			    special = FROM_UNIXTIME(sys_log.tstamp, '%d.%m.%Y %h:%i:%s')
			}

			details {
				table = sys_log
				field = details
			}

			username {
				table = fe_users
				field = username
			}

		}

		columns {

			10 {
				label = Date
				columnIdentifier = readableDate
				fieldIdentifier = readableDate
			}


			20 {
				label = User
				columnIdentifier = username
				fieldIdentifier = username
			}

			30 {
				label = Details
				columnIdentifier = details
				fieldIdentifier = details
			}
		}

	    pager.itemsPerPage = 100

		filters {
			filterBox1 {
				filterConfigs {

					10 < plugin.tx_ptextlist.prototype.filter.dateSelectList
					10 {
						filterIdentifier = tstamp
						label = Timespan
						fieldIdentifier = tstamp

						dateIteratorStart = 1227999510
                        dateIteratorEnd = 1314607478
                        dateIteratorIncrement = m
                        dateIteratorFormat = %B %Y

						inactiveOption = [ALL]
						inactiveValue = ---ALL---
					}
				}
			}
		}
	}
}