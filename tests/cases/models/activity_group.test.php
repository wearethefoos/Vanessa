<?php

/* ActivityGroup Test cases generated on: 2010-11-26 16:11:02 : 1290785402*/
App::import('Model', 'ActivityGroup');

class ActivityGroupTestCase extends CakeTestCase {
	var $fixtures = array('app.activity_group', 'app.preference', 'app.student_group', 'app.course', 'app.user', 'app.student', 'app.join_student_group', 'app.students_course', 'app.role', 'app.security_log', 'app.activity', 'app.join_activity_group');

	function startTest() {
		$this->ActivityGroup =& ClassRegistry::init('ActivityGroup');
	}

	function endTest() {
		unset($this->ActivityGroup);
		ClassRegistry::flush();
	}

	function testGetCourseIdFromId() {
		$course_id = $this->ActivityGroup->getCourseIdFromId(1);
		$this->assertEqual($course_id, 1);
	}

}
?>