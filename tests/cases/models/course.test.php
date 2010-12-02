<?php

/* Course Test cases generated on: 2010-11-26 12:11:24 : 1290769224*/
App::import('Model', 'Course');

class CourseTestCase extends CakeTestCase {
	var $fixtures = array('app.course', 'app.user', 'app.activity', 'app.join_activity_group', 'app.student_group', 'app.join_student_group', 'app.preference', 'app.activity_group', 'app.role', 'app.security_log', 'app.student', 'app.students_course');

	function startTest() {
		$this->Course =& ClassRegistry::init('Course');
	}
	
	function testGetStudentsCourses() {
		$count = count($this->Course->getStudentsCourses(1));
		$this->assertEqual($count, 1);
	}

	function endTest() {
		unset($this->Course);
		ClassRegistry::flush();
	}

}
?>