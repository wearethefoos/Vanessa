<?php
/* JoinActivityGroup Fixture generated on: 
2010-11-26 12:11:16 : 1290769276 */
class JoinActivityGroupFixture extends CakeTestFixture {
	var $name = 'JoinActivityGroup';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'activity_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'activity_group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'activity_id' => array('column' => 'activity_id', 'unique' => 0), 'activity_group_id' => array('column' => 'activity_group_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'activity_id' => 1,
			'activity_group_id' => 1
		),
		array(
			'id' => 2,
			'activity_id' => 2,
			'activity_group_id' => 2
		),
		array(
			'id' => 3,
			'activity_id' => 3,
			'activity_group_id' => 3
		),
		array(
			'id' => 4,
			'activity_id' => 4,
			'activity_group_id' => 4
		),
		array(
			'id' => 5,
			'activity_id' => 5,
			'activity_group_id' => 5
		),
		array(
			'id' => 6,
			'activity_id' => 6,
			'activity_group_id' => 6
		),
		array(
			'id' => 7,
			'activity_id' => 7,
			'activity_group_id' => 7
		),
		array(
			'id' => 8,
			'activity_id' => 8,
			'activity_group_id' => 8
		),
		array(
			'id' => 9,
			'activity_id' => 9,
			'activity_group_id' => 9
		),
		array(
			'id' => 10,
			'activity_id' => 10,
			'activity_group_id' => 10
		),
		array(
			'id' => 11,
			'activity_id' => 11,
			'activity_group_id' => 11
		),
		array(
			'id' => 12,
			'activity_id' => 12,
			'activity_group_id' => 12
		),
	);
}
?>