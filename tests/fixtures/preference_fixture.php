<?php
/* Preference Fixture generated on: 
Warning: date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected 'Europe/Berlin' for 'CET/1.0/no DST' instead in /Applications/XAMPP/xamppfiles/share/cakephp/cake/console/templates/default/classes/fixture.ctp on line 24
2010-11-26 12:11:36 : 1290769236 */
class PreferenceFixture extends CakeTestFixture {
	var $name = 'Preference';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'student_group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'activity_group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'unwantedness' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'student_group_id' => array('column' => 'student_group_id', 'unique' => 0), 'activity_group_id' => array('column' => 'activity_group_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'student_group_id' => 1,
			'activity_group_id' => 1,
			'unwantedness' => 1
		),
	);
}
?>