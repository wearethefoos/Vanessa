<?php

/* Activities Test cases generated on: 2010-11-26 14:11:31 : 1290779971*/
App::import('Controller', 'Activities');

class TestActivitiesController extends ActivitiesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ActivitiesControllerTestCase extends CakeTestCase {
	var $fixtures = array(
		'app.activity', 
		'app.course', 
		'app.user', 
		'app.student', 
		'app.join_student_group', 
		'app.student_group', 
		'app.preference', 
		'app.activity_group', 
		'app.students_course', 
		'app.role', 
		'app.security_log', 
		'app.join_activity_group'
		);

	function startTest() {
		$this->Activities =& new TestActivitiesController();
		$this->Activities->constructClasses();
	}

	function endTest() {
		unset($this->Activities);
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