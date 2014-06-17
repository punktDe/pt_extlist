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

				field1 {
					special = field1
				}

				field2 {
					special = field2
				}

				field3 {
					special = field3
				}

				field4 {
					special = field4
				}

				field5 {
					special = field5
				}

    		}

    		columns {
    			10 {
    				columnIdentifier = field1
    				fieldIdentifier = field1, field2, field3
    			}

    			20 {
					columnIdentifier = field2
					fieldIdentifier = field2
				}

    			30 {
					columnIdentifier = field3
					fieldIdentifier = field3
				}

    			40 {
					columnIdentifier = field4
					fieldIdentifier = field4
				}

    			50 {
					columnIdentifier = field5
					fieldIdentifier = field5
				}
    		}



    		pager {
    			itemsPerPage = 0
    		}
    	}
    }
}