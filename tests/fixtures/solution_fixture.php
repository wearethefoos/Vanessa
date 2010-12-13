<?php
/* Solution Fixture generated on: 
Warning: date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected 'Europe/Berlin' for 'CET/1.0/no DST' instead in /Applications/XAMPP/xamppfiles/share/cakephp/cake/console/templates/default/classes/fixture.ctp on line 24
2010-12-13 10:12:44 : 1292233064 */
class SolutionFixture extends CakeTestFixture {
	var $name = 'Solution';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'student_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'unwantedness' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'activity_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'course_id' => array('column' => 'course_id', 'unique' => 0), 'student_id' => array('column' => 'student_id', 'unique' => 0), 'activity_id' => array('column' => 'activity_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'course_id' => 1,
			'student_id' => 1,
			'unwantedness' => 1,
			'activity_id' => 1,
			'created' => '2010-12-13 10:37:44',
			'modified' => '2010-12-13 10:37:44'
		),
	);
}
?>