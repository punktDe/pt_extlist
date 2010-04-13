<?php
assert_options (ASSERT_ACTIVE, 1);
assert_options (ASSERT_WARNING, 0);
assert_options (ASSERT_BAIL, 0);
assert_options(ASSERT_CALLBACK, array('Tx_PtExtlist_ActiveAssertion','assertionFailed'));
		
class Tx_PtExtlist_ActiveAssertion extends PHPUnit_Framework_Assert {
	
	protected static $assertions;
	
	public static function assertionFailed($file, $line, $message) {
		$class = substr($file, strpos($file, 'Classes/')+8);
		$class = substr($class, 0, strlen($class)-4);
		
		$class = str_replace('/','_',$class);

		self::$assertions['Tx_PtExtlist_'.$class][$line] = $message;
	}
	
	public function assertFailedAssertion($class, $methodName) {
		
		$method = new ReflectionMethod($class, $methodName);

		$start = $method->getStartLine();
		$end = $method->getEndLine();
				
		$this->assertTrue(array_key_exists($class, self::$assertions));
		
		$keys = array_keys(self::$assertions[$class]);
		$line = $keys[0];
		
		$betweenLines = false;
		if($start <= $line && $end >= $line) {
			$betweenLines = true;
		}
		
		$this->assertTrue($betweenLines);
	}
	
}

?>