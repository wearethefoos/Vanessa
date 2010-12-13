<?php

/* SecurityLog Test cases generated on: 2010-11-26 12:11:49 : 1290769249*/
App::import('Model', 'SecurityLog');

class SecurityLogTestCase extends CakeTestCase {
	var $fixtures = array(
		'app.security_log', 
		'app.user'
		);

	function startTest() {
		$this->SecurityLog =& ClassRegistry::init('SecurityLog');
	}

	function endTest() {
		unset($this->SecurityLog);
		ClassRegistry::flush();
	}

}
?>