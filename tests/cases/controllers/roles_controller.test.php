<?php

/* Roles Test cases generated on: 2010-11-26 12:11:42 : 1290769242*/
App::import('Controller', 'Roles');

class TestRolesController extends RolesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class RolesControllerTestCase extends CakeTestCase {
	var $fixtures = array(
		'app.role', 
		'app.user', 
		'app.student', 
		'app.join_student_group', 
		'app.student_group', 
		'app.course', 
		'app.activity', 
		'app.join_activity_group', 
		'app.activity_group', 
		'app.preference', 
		'app.students_course', 
		'app.security_log'
		);

	function startTest() {
		$this->Roles =& new TestRolesController();
		$this->Roles->constructClasses();
	}

	function endTest() {
		unset($this->Roles);
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