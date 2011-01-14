<?php
class Activity extends AppModel {
	var $name = 'Activity';
	var $validate = array(
		'course_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'This cannnot be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'room' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'This cannnot be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'max_participants' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'This must be a numeric value',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'min_participants' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'This must be a numeric value',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
         'smaller' => array(
            'rule' => array('MinSmallerThanMax'),
            'message' => 'Min participants must be smaller than Max participants'
         )
		),
      'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'This cannnot be empty')
      )
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Course' => array(
			'className' => 'Course',
			'foreignKey' => 'course_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

   function MinSmallerThanMax($check) {
      if ($this->data['Activity']['max_participants'] < $this->data['Activity']['min_participants'])
         return false;
      else
         return true;
   }

	public function getCourseIdFromId($id=null) {
		if ($id) {
			if ($activity = $this->findById($id)) {
				return $activity['Activity']['course_id'];
			}
		}
		return false;
	}

   public function getActivityListFromCourse($course_id) {
      $rows = $this->find(
		 'all',
         array(
            'conditions' => array('course_id' => $course_id),
            'recursive' => -1,
            'fields' => array('id', 'name', 'title', 'min_participants', 'max_participants')));
      $result = array();
      foreach($rows as &$row) {
         $result[$row['Activity']['id']] = array(
            'name' => trim($row['Activity']['name']),
            'title' => $row['Activity']['title'],
            'min_participants' => $row['Activity']['min_participants'],
            'max_participants' => $row['Activity']['max_participants'],
            );
      }
      return $result;
   }

}
?>