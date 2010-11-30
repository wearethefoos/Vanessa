<?php

/* SecurityLogs Test cases generated on: 2010-11-26 12:11:49 : 1290769249*/
App::import('Controller', 'SecurityLogs');

class TestSecurityLogsController extends SecurityLogsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SecurityLogsControllerTestCase extends CakeTestCase {
	var $fixtures = array(
		'app.security_log', 
		'app.user', 
		'app.student', 
		'app.join_student_group', 
		'app.student_group',
		'app.course', 
		'app.activity', 
		'app.activity_group', 
		'app.join_activity_group', 
		'app.preference', 
		'app.students_course', 
		'app.course', 
		'app.role'
		);

	function startTest() {
		$this->SecurityLogs =& new TestSecurityLogsController();
		$this->SecurityLogs->constructClasses();
	}

	function endTest() {
		unset($this->SecurityLogs);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>