<?php

/* Courses Test cases generated on: 2010-11-26 12:11:24 : 1290769224*/
App::import('Controller', 'Courses');

class TestCoursesController extends CoursesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class CoursesControllerTestCase extends CakeTestCase {
	var $fixtures = array(
		'app.course', 
		'app.user', 
		'app.activity', 
		'app.join_activity_group', 
		'app.student_group', 
		'app.join_student_group', 
		'app.student', 
		'app.students_course', 
		'app.preference', 
		'app.activity_group', 
		'app.role', 
		'app.security_log'
		);

	function startTest() {
		$this->Courses =& new TestCoursesController();
		$this->Courses->constructClasses();
	}

	function endTest() {
		unset($this->Courses);
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