####################################################
# This template configures a demolist for use
# with pt_extlist for rendering ExtBase Domain objects
#
# @author Michael Knoll <knoll@punkt.de>
# @Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

config.tx_extbase {
	persistence{

		enableAutomaticCacheClearing = 1
		updateReferenceIndex = 0
		classes {
			Tx_Extbase_Domain_Model_FrontendUser {
				mapping {
					tableName = fe_users
					recordType = Tx_Extbase_Domain_Model_FrontendUser
					columns {
						lockToDomain.mapOnProperty = lockToDomain
					}
				}
			}
			Tx_Extbase_Domain_Model_FrontendUserGroup {
				mapping {
					tableName = fe_groups
					recordType =
					columns {
						lockToDomain.mapOnProperty = lockToDomain
					}
				}
			}
		}
	}
}

plugin.tx_ptextlist.persistence.storagePid = 14

plugin.tx_ptextlist.settings {

	listConfig.demolist_extbase_01 {

		backendConfig < plugin.tx_ptextlist.prototype.backend.extbase
		backendConfig {

			dataBackendClass = ExtBaseDataBackend_ExtBaseDataBackend
			dataMapperClass = Mapper_DomainObjectMapper
			queryInterpreterClass = ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter
			repositoryClassName = TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository

			dataSource {
			}

			tables (
			)

			baseFromClause (
			)

			baseWhereClause (
			)

			baseGroupByClause (
			)
		}

		fields {
			title {
				table = __self__
				field = title
			}
		}

		columns {
			10 {
				fieldIdentifier = title
				columnIdentifier = title
				label = Titel
			}
		}

		filters {
			filterbox1 {
				filterConfigs {
					10 < plugin.tx_ptextlist.prototype.filter.string
					10 {
						filterIdentifier = filter1
						label = Gruppentitel
						fieldIdentifier = title
					}
				}
			}
		}

		pager {
			itemsPerPage = 30
		}
	}
}
