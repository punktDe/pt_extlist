####################################################
# This template configures a demolist for use
# with pt_extlist
#
# You have to add a fullText index to the table to get the demo runnig. Use the following query:
# ALTER TABLE `t3develop`.`cache_extensions` ADD FULLTEXT `FullText` (`extkey` ,`title` ,`description` ,`authorname` ,`uploadcomment` )
#
# @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################


plugin.tx_ptextlist.settings {

	listConfig.demolist_typo3_09_bigdata {

		useIterationListData = 1

		backendConfig < plugin.tx_ptextlist.prototype.backend.typo3
		backendConfig {

			tables (
				cache_extensions
			)
		}


		fields {

			extkey {
				table = cache_extensions
				field = extkey
			}

			version {
				table = cache_extensions
				field = version
			}

			title {
				table = cache_extensions
				field = title
			}

			description {
				table = cache_extensions
				field = description
			}

			authorname {
				table = cache_extensions
				field = authorname
			}

			authoremail {
				table = cache_extensions
				field = authoremail
			}

			ownerusername {
				table = cache_extensions
				field = ownerusername
			}

			uploadcomment {
				table = cache_extensions
				field = uploadcomment
			}

			authorcompany {
				table = cache_extensions
				field = authorcompany
			}

			category {
			    table = cache_extensions
			    field = category
			}
		}

		columns {

			10 {
				label = ExtKey
				columnIdentifier = extkex
				fieldIdentifier = extkey
			}

			15 {
				label = Version
				columnIdentifier = version
				fieldIdentifier = version
			}


			20 {
				label = Title
				columnIdentifier = title
				fieldIdentifier = title
			}

			25 {
				label = Owner Username
				columnIdentifier = ownerusername
				fieldIdentifier = ownerusername
			}

			30 {
				label = Author
				columnIdentifier = authorname
				fieldIdentifier = authorname
			}

			35 {
				label = Author email
				columnIdentifier = authorrmail
				fieldIdentifier = authoremail
			}

			40 {
				label = Description
				columnIdentifier = description
				fieldIdentifier = description
			}

			50 {
				label = Uploadcomment
				columnIdentifier = uploadcomment
				fieldIdentifier = uploadcomment
			}

			60 {
				label = Author Company
				columnIdentifier = authorcompany
				fieldIdentifier = authorcompany
			}

			70 {
				label = Category
				columnIdentifier = category
				fieldIdentifier = category
			}

		}

	    pager.itemsPerPage = 10

	}
}



plugin.tx_ptextlist.settings.listConfig.demolist_typo3_09_bigdata_export < plugin.tx_ptextlist.settings.listConfig.demolist_typo3_09_bigdata
plugin.tx_ptextlist.settings.listConfig.demolist_typo3_09_bigdata_export {
	pager.itemsPerPage = 0
}