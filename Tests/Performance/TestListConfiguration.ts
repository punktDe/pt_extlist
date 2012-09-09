plugin.tx_ptextlist.settings {

	listConfig {

	   performanceTestList {

		   	backendConfig {
				dataBackendClass = Tx_PtExtlist_Tests_Performance_TestDataBackend
				dataMapperClass = Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper
				dataSourceClass = Tx_PtExtlist_Tests_Performance_TestDataBackend
				queryInterpreterClass = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter
			}

    		fields {

				col_1 {
					special = col_1
				}

				col_2 {
					special = col_2
				}

				col_3 {
					special = col_3
				}

				col_4 {
					special = col_4
				}

				col_5 {
					special = col_5
				}

    		}

    		columns {
    			10 {
    				columnIdentifier = col_1
    				fieldIdentifier = col_1, col_2, col_3
    			}

    			20 {
					columnIdentifier = col_2
					fieldIdentifier = col_2
				}

    			30 {
					columnIdentifier = col_3
					fieldIdentifier = col_3
				}

    			40 {
					columnIdentifier = col_4
					fieldIdentifier = col_4
				}

    			50 {
					columnIdentifier = col_5
					fieldIdentifier = col_5
				}
    		}



    		pager {
    			itemsPerPage = 0
    		}
    	}
    }
}