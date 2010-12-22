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

	/**
	 * Add a classmate to a student's group.
	 * 
	 * @param int $course_id: the id of the course.
	 * @param int $student_id: the id of the classmate.
	 * @return boolean success.
	 */
	public function addClassmateToMyGroup($course_id, $student_id) {
		if ($this->__checkIfStudentFollowsThisCourse($course_id) &&
			$this->__checkIfStudentFollowsThisCourse($course_id, $student_id)
		) {
			if (!$group_id = $this->Course->getStudentsGroupIdForThisCourse($course_id)) {
				$this->log('Creating a group first');
				$group_id = $this->createGroupForCourse($course_id);
			}
			if ($group_id) {
				$this->JoinStudentGroup->create();
				if ($this->JoinStudentGroup->save(array('student_id' => $student_id, 'student_group_id' => $group_id))) {
					return true;
				}
				$this->log('Could not add student to the group.. dunno why ^^');
			}
			$this->log('Could not determine group id');
		}
		
		$this->log(sprintf('Student %s does not follow course %s or the group organizer %s does not follow this course, which is even weirder.. >.<', 
			$student_id, $course_id, $this->getStudentIdFromSession()));
		
		return false;
	}
	
	/**
	 * Create a group for the logged in student for a specified course.
	 * 
	 * @param int $course_id: the id of the associated course.
	 * @return mixed The group id (int) | false on errors.
	 */
	public function createGroupForCourse($course_id) {
		if ($this->__checkIfStudentFollowsThisCourse($course_id)) {
			if ($group_id = $this->Course->getStudentsGroupIdForThisCourse($course_id)) {
				$this->log('Student is already in a group');
				return $group_id;
			}
			$student_id = $this->getStudentIdFromSession();
			$this->create();
			if ($this->save(array('course_id' => $course_id))) {
				$this->JoinStudentGroup->create();
				if ($this->JoinStudentGroup->save(array('student_id' => $student_id, 'student_group_id' => $this->id))) {
					return $this->id;
				}
				$this->log('Could not create student <-> group association.');
			}
			$this->log('Could not create group!');
		}
		return false;		
	}
	
	/**
	 * Check if the logged in student is in a group.
	 * 
	 * @param int $course_id
	 * @return boolean
	 */
	public function checkIfStudentIsGrouped($course_id) {
		if ($group_id = $this->Course->getStudentsGroupIdForThisCourse($course_id)) {
			return ($this->JoinStudentGroup->find('count', array(
				'conditions' => array(
					'JoinStudentGroup.student_group_id' => $group_id,
				)
			)) > 1);
		}
		return false;
	}
	
	/**
	 * Withdraw from a group.
	 * 
	 * @param int $group_id: the student_group_id.
	 * @return boolean success
	 */
	public function withdrawFromGroup($group_id) {
		return ($this->JoinStudentGroup->deleteAll(array(
			'JoinStudentGroup.student_id' => $this->getStudentIdFromSession(),
			'JoinStudentGroup.student_group_id' => $group_id,
		)));
	}
	
	/**
	 * Get the preferences for the student's group on a specific course.
	 * 
	 * @param int $course_id: the course's id
	 * @return array with preferences
	 */
	public function getPreferences($course_id) {
		$preferences = array();
		
		if ($group_id = $this->Course->getStudentsGroupIdForThisCourse($course_id)) {
			$preferences = $this->Preference->find('all', array(
				'conditions' => array(
					'Preference.student_group_id' => $group_id,
				)
			));
		}
		
		return $preferences;
	}
	
	/** 
	 * check if student is associated with a course 
	 * 
	 * @param int $course_id: the id of the course.
	 * @param int $student_id [optional]: the id of the student - defaults to the logged in student.
	 * @return boolean
	 */
	private function __checkIfStudentFollowsThisCourse($course_id, $student_id=null) {
		if (!$student_id) {
			$student_id = $this->getStudentIdFromSession();
		}
		
		return ($this->Course->StudentsCourse->find('count', array(
			'conditions' => array(
				'StudentsCourse.course_id' => $course_id,
				'StudentsCourse.student_id' => $student_id,
			))) > 0);
	}

}
?>