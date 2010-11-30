<?php
/* JoinStudentGroup Fixture generated on: 
Warning: date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected 'Europe/Berlin' for 'CET/1.0/no DST' instead in /Applications/XAMPP/xamppfiles/share/cakephp/cake/console/templates/default/classes/fixture.ctp on line 24
2010-11-26 12:11:19 : 1290769279 */
class JoinStudentGroupFixture extends CakeTestFixture {
	var $name = 'JoinStudentGroup';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'student_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'student_group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'student_id' => array('column' => 'student_id', 'unique' => 0), 'student_group_id' => array('column' => 'student_group_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'student_id' => 1,
			'student_group_id' => 1
		),
	);
}
?>