####################################################
# This template configures a demolist for use
# with pt_extlist
#
# You have to add a fullText index to the table to get the demo runnig. Use the following query:
# ALTER TABLE `t3develop`.`tx_extensionmanager_domain_model_extension` ADD FULLTEXT `FullText` (`extkey` ,`title` ,`description` ,`authorname` ,`uploadcomment` )
#
# @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################


plugin.tx_ptextlist.settings {

	listConfig.demolist_typo3_03_fullTextSearch {

		backendConfig < plugin.tx_ptextlist.prototype.backend.typo3
		backendConfig {

			tables (
				tx_extensionmanager_domain_model_extension
			)

			baseGroupByClause (
                extension_key
            )
		}


		fields {

			extkey {
				table = tx_extensionmanager_domain_model_extension
				field = extension_key
			}

			title {
				table = tx_extensionmanager_domain_model_extension
				field = title
			}

			description {
				table = tx_extensionmanager_domain_model_extension
				field = description
			}

			authorname {
				table = tx_extensionmanager_domain_model_extension
				field = author_name
			}

			uploadcomment {
				table = tx_extensionmanager_domain_model_extension
				field = update_comment
			}

			category {
			    table = tx_extensionmanager_domain_model_extension
			    field = category
			}
		}

		columns {

			10 {
				label = ExtKey
				columnIdentifier = extkex
				fieldIdentifier = extkey
			}


			20 {
				label = Title
				columnIdentifier = title
				fieldIdentifier = title
			}

			30 {
				label = Author
				columnIdentifier = authorname
				fieldIdentifier = authorname
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

		}

	    pager.itemsPerPage = 30

		filters {
			filterBox1 {
				filterConfigs {

					10 < plugin.tx_ptextlist.prototype.filter.select
					10 {
						filterIdentifier = authorname
						label = Author
						fieldIdentifier = authorname
						inactiveOption = [ALL]
						inactiveValue = ---ALL---
					}

					15 < plugin.tx_ptextlist.prototype.filter.select
					15 {
						filterIdentifier = category
						label = Plugintyp
						fieldIdentifier = category
						inactiveOption = [ALL]
						inactiveValue = ---ALL---

						options {
						    0 = Backend
						    3 = Frontend-Plug-Ins
						}
					}



					20 < plugin.tx_ptextlist.prototype.filter.fullText
					20 {
						filterIdentifier = filter1
						label = Fulltext Search
						fieldIdentifier = extkey,title,description,authorname,uploadcomment
						booleanMode = 1
						booleanModeWrapWithStars = 1
						minWordLength = 3
					}
				}
			}
		}
	}
}



################################
# Localization Override
################################

plugin.tx_ptextlist._LOCAL_LANG{
	default {
		emptyList = List is empty.
	}
	de {
		emptyList = Liste ist leer.
	}
}
