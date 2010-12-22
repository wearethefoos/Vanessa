<?php
class Course extends AppModel {
   var $actsAs = array('Containable');
	var $name = 'Course';
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
		'amount_of_preferences' => array(
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
		'Supervisor' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Activity' => array(
			'className' => 'Activity',
			'foreignKey' => 'course_id',
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
		'StudentGroup' => array(
			'className' => 'StudentGroup',
			'foreignKey' => 'course_id',
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
		'StudentsCourse' => array(
			'className' => 'StudentsCourse',
			'foreignKey' => 'course_id',
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


	var $hasAndBelongsToMany = array(
		'Student' => array(
			'className' => 'Student',
			'joinTable' => 'students_courses',
			'foreignKey' => 'course_id',
			'associationForeignKey' => 'student_id',
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
	
	function fromUserWithId($id=null, $list=false) {
		if ($id) {
			$list = $list ? 'list' : 'all';
			return $this->find($list, array(
				'conditions' => array(
					'Course.user_id' => $id,
				)
			));
		}
		return false;
	}
	
	function getStudentsPreferences($id) {
		$student_group_id = $this->getStudentsGroupIdForThisCourse($id);
		return $this->StudentGroup->find('all', array(
			'conditions' => array(
				'StudentGroup.course_id' => $id,
				'StudentGroup.id' => $student_group_id,
			)
		));
	}
	
	/**
	 * Get students_group id on a specific course for a logged in student.
	 * 
	 * @param $course_id
	 * 
	 * @return mixed students_group_id | false
	 */
	public function getStudentsGroupIdForThisCourse($id) {
		if ($student_id = $this->getStudentIdFromSession()) {
			if ($group = $this->StudentGroup->JoinStudentGroup->find('first', array(
				'conditions' => array(
					'JoinStudentGroup.student_id' => $student_id,
					'StudentGroup.course_id' => $course_id,
					)
				))) {
					return $group['StudentGroup']['id'];
				}
		} else {
			return false;
		}
		
	}

   function findCourse($course_id) {
      return $this->find('first', array(
         'conditions' => array(
            'Course.id' => $course_id
            ),
         'contain' => array(
            'Student.User',
            'Supervisor'
            )
         ));
   }

   function findStudents($course_id) {
      $students = $this->find('first', array(
         'conditions' => array(
            'id' => $course_id
            ),
         'contain' => array(
            'Student.User',
            )
         ));
      $students = (isset($students['Student']) && count($students['Student'])) ? $students['Student'] : array();

      return $students;
   }

}
?>