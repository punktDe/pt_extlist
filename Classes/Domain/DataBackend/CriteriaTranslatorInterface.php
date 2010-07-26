<?php

interface Tx_PtExtlist_Domain_DataBackend_CriteriaTranslatorInterface {
	
	public static function translateCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria);
	
}

?>