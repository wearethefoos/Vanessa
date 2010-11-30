<?php
class ActivityGroup extends AppModel {
	var $name = 'ActivityGroup';
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
	

	var $hasMany = array('Preference', 'JoinActivityGroup');
	
	var $hasAndBelongsToMany = array(
		'Activity' => array(
			'className' => 'Activity',
			'joinTable' => 'join_activity_groups',
			'foreignKey' => 'activity_group_id',
			'associationForeignKey' => 'activity_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	/**
	 * Get the associated course id for an activity group through one of the
	 * activities in the group.
	 */
	function getCourseIdFromId($id) {
		if ($group = $this->JoinActivityGroup->find('first', array(
			'conditions' => array(
				'JoinActivityGroup.activity_group_id' => $id,
				)
			))) {
			if (isset($group['Activity']['course_id'])) {
				return $group['Activity']['course_id'];
			}
		}
		return false;
	}

}
?>