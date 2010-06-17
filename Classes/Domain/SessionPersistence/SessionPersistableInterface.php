<?php
interface Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface {
	
    public function persistToSession();
    public function getSessionNamespace();
    public function loadFromSession(array $sessionData);
	
}
?>