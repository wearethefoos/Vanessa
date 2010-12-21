<?php
class StudentGroup extends AppModel {
	var $name = 'StudentGroup';
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

	var $hasMany = array(
		'Preference' => array(
			'className' => 'Preference',
			'foreignKey' => 'student_group_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'JoinStudentGroup' => array(
			'className' => 'JoinStudentGroup',
			'foreignKey' => 'student_group_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
   
   public function getStudentGroupListFromCourse($course_id) {
      $rows = $this->findAll(array(
         'conditions' => array('StudentGroup.course_id' => $course_id),
         'recursive' => 1
      ));

      $result = array();
      foreach ($rows as &$row) {
         $group = array();
         $preferences = array();
         foreach ($row['Preference'] as $preference) {
            $preferences[$preference['unwantedness']] = $preference['activity_id'];
         }
         foreach ($row['JoinStudentGroup'] as $students) {
            $group[] = $students['student_id'];
         }
         $result[$row['StudentGroup']['id']] = array('group' => $group, 'preferences' => $preferences);
      }

      return $result;
   }

}
?>