<?php
/* Activity Fixture generated on: 
2010-11-26 11:11:15 : 1290769155 */
class ActivityFixture extends CakeTestFixture {
	var $name = 'Activity';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8, 'key' => 'primary', 'comment' => 'The Id'),
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'teacher' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'room' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 16, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'max_participants' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2, 'comment' => 'Maximum number students that may take part.'),
		'min_participants' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2, 'comment' => 'Minium number of students taking part.'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'course_id' => array('column' => 'course_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 2,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 3,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 4,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 5,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 6,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 7,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 8,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 9,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 10,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 11,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
		array(
			'id' => 12,
			'course_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'teacher' => 'Lorem ipsum dolor sit amet',
			'room' => 'Lorem ipsum do',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'max_participants' => 1,
			'min_participants' => 1
		),
	);
}
?>