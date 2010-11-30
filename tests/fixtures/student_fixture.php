<?php
/* Student Fixture generated on: 
2010-11-26 12:11:03 : 1290769263 */
class StudentFixture extends CakeTestFixture {
	var $name = 'Student';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8, 'key' => 'primary', 'comment' => 'Key value. (can sometimes agree with college kaart)'),
		'coll_kaart' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8, 'key' => 'index', 'comment' => 'College kaart number.'),
		'ldap_uid' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'comment' => 'UID for student in LDAP (usually agrees (numerically) with college kaart number)', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'inx_coll_kaart' => array('column' => 'coll_kaart', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'coll_kaart' => 1,
			'ldap_uid' => 'Lorem ipsum dolor '
		),
	);
}
?>