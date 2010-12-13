<?php

/* Solution Test cases generated on: 2010-12-13 10:12:44 : 1292233064*/
App::import('Model', 'Solution');

class SolutionTestCase extends CakeTestCase {
	var $fixtures = array('app.solution', 'app.course', 'app.user', 'app.student', 'app.join_student_group', 'app.student_group', 'app.preference', 'app.activity', 'app.students_course', 'app.role', 'app.security_log');

	function startTest() {
		$this->Solution =& ClassRegistry::init('Solution');
	}

	function endTest() {
		unset($this->Solution);
		ClassRegistry::flush();
	}

}
?>