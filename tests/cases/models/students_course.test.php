<?php

/* Course Test cases generated on: 2010-11-26 12:11:24 : 1290769224*/
App::import('Model', 'StudentsCourse');

class StudentsCourseTestCase extends CakeTestCase {
	var $fixtures = array(
		'app.course', 
		'app.user', 
		'app.activity', 
		'app.student_group', 
		'app.join_student_group', 
		'app.preference', 
		'app.role', 
		'app.security_log', 
		'app.student', 
		'app.students_course'
		);

	function startTest() {
		$this->StudentsCourse =& ClassRegistry::init('StudentsCourse');
	}
	
	function testGetStudentsCourses() {
		$courses = $this->StudentsCourse->getStudentsCourses(1);

		/* student 1 should be associated to 1 course, so.. */
		$this->assertEqual(count($courses), 1);
	}

	function endTest() {
		unset($this->Course);
		ClassRegistry::flush();
	}

}
?>