<?php

/* Role Test cases generated on: 2010-11-26 12:11:42 : 1290769242*/
App::import('Model', 'Role');

class RoleTestCase extends CakeTestCase {
	var $fixtures = array(
		'app.role', 
		'app.user'
		);

	function startTest() {
		$this->Role =& ClassRegistry::init('Role');
	}

	function endTest() {
		unset($this->Role);
		ClassRegistry::flush();
	}

}
?>