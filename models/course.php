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
      'amount_of_dislikes' => array(
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
		'Group' => array(
			'className' => 'Group',
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
		return $this->StudentGroup->Preference->find('all', array(
			'conditions' => array(
				'Preference.student_group_id' => $student_group_id,
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
			if ($group = $this->Group->find('first', array(
				'conditions' => array(
					'Student.id' => $student_id,
					'Group.course_id' => $id,
					)
				))) {
					return $group['Group']['id'];
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
            'Group.Student.User',
            'Supervisor'
            )
         ));
   }

   function findStudents($course_id) {
      $groups = $this->find('all', array(
         'conditions' => array(
            'Course.id' => $course_id
            ),
//         'contain' => array(
//            'Group.Student.User',
//            ),
         'recursive' => 0,
         'joins' => array(
               array(
                  'table' => 'groups',
                  'alias' => 'Group',
                  'type' => 'INNER',
                  'conditions' => array('Group.course_id = Course.id')
               ),
               array(
                  'table' => 'join_student_groups',
                  'alias' => 'JoinStudentGroup',
                  'type' => 'INNER',
                  'conditions' => array('JoinStudentGroup.group_id = Group.id')
               ),
               array(
                  'table' => 'students',
                  'alias' => 'Student',
                  'type' => 'INNER',
                  'conditions' => array('Student.id = JoinStudentGroup.student_id')
               ),
               array(
                  'table' => 'users',
                  'alias' => 'User',
                  'type' => 'INNER',
                  'conditions' => array('Student.id = User.student_id')
               ),
         ),
         'fields' => array(
            'Group.*',
            'Student.*',
            'User.*'
         )
         ));

      $students = array();
      foreach ($groups as $group) {
         $group['Student']['User'] = $group['User'];
         $students[] = $group['Student'];
      }
      return $students;
   }

   function getStudentList($course_id) {
      $result = array();
      $students = $this->findStudents($course_id);
      foreach($students as $student) {
         $result[$student['id']] = array('number' => $student['coll_kaart'], 'name' => $student['User']['name']);
      }

      return $result;
   }

   function isStudentInCourse($course_id, $student_id) {
      $students = $this->findStudents($course_id);
      $found = false;
      foreach($students as $student) {
         if ($student['id'] == $student_id) {
            $found = true;
            break;
         }
      }
      return $found;
   }

}
?>