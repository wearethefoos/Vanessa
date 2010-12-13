<?php

/* Solutions Test cases generated on: 2010-12-13 10:12:44 : 1292233064*/
App::import('Controller', 'Solutions');

class TestSolutionsController extends SolutionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SolutionsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.solution', 'app.course', 'app.user', 'app.student', 'app.join_student_group', 'app.student_group', 'app.preference', 'app.activity', 'app.students_course', 'app.role', 'app.security_log');

	function startTest() {
		$this->Solutions =& new TestSolutionsController();
		$this->Solutions->constructClasses();
	}

	function endTest() {
		unset($this->Solutions);
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