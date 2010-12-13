<?php
/**
 * Short description for file.
 *
 * The role model.
 *
 * PHP versions 4 and 5
 *
 * TOP : Technical Support Group of the Faculty of Behavioral Sciences at 
 * 		 the University of Amsterdam (http://www.top.fmg.uva.nl)
 * Copyright 2009-2010, Faculty of Behavioral Sciences, University of 
 *       Amsterdam (http://www.fmg.uva.nl)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     University of Amsterdam (http://www.top.fmg.uva.nl)
 * @link          http://www.top.fmg.uva.nl (Technical Support Group of the Faculty of 
 * 			      Behavioral Sciences at the University of Amsterdam
 * @package       cake
 * @subpackage    cake.vanessa
 * @since         VANESSA v.0.0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 *
 * Roles are ACL requesters, most of the ACL goes through the Roles as they are the Users parents.
 * 
 * There are two types of Users: those imported from the LDAP, and those created in VANESSA.
 *
 * @package       cake
 * @subpackage    cake.vanessa
 * @link		  https://knack.fmg.uva.nl/trac/april/browser/trunk/va/models/role.php
 * @author		  W.R.deVos@uva.nl
 */
class Role extends AppModel {
	var $name = 'Role';
	var $actsAs = array('Acl' => array('type' => 'requester'));
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'role_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
    function parentNode() {
        return null;
    }

}
?>