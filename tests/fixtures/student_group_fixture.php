<?php
/* StudentGroup Fixture generated on: 
2010-11-26 12:11:58 : 1290769258 */
class StudentGroupFixture extends CakeTestFixture {
	var $name = 'StudentGroup';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'course_id' => array('column' => 'course_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'course_id' => 1
		),
	);
}
?>