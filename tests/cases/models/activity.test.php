<?php

/* Activity Test cases generated on: 2010-11-26 11:11:15 : 1290769155*/
App::import('Model', 'Activity');

class ActivityTestCase extends CakeTestCase {
	var $fixtures = array(
		'app.activity', 
		'app.user', 
		'app.course'
		);

	function startTest() {
		$this->Activity =& ClassRegistry::init('Activity');
	}

	function endTest() {
		unset($this->Activity);
		ClassRegistry::flush();
	}

}
?>