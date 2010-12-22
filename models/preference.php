<?php
class Preference extends AppModel {
	var $name = 'Preference';
	var $validate = array(
		'student_group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'activity_group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'unwantedness' => array(
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
		'StudentGroup' => array(
			'className' => 'StudentGroup',
			'foreignKey' => 'student_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Activity' => array(
			'className' => 'Activity',
			'foreignKey' => 'activity_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
	 * Save preferences
	 * 
	 * @param array $preferences
	 * @param array $dislikes
	 * @return boolean
	 */
	function savePreferences($likes=array(), $dislikes=array()) {
		return ($this->writeLikes($likes) && $this->writeDislikes($dislikes));
	}
	
	/**
	 * Write preferences to data source
	 * 
	 * @param array $preferences
	 * @return boolean
	 */
	public function writeLikes($likes=array()) {
		foreach ($likes as $unwantedness => $like) {
			if (!$this->writePreference($like, $unwantedness)) {
				$this->log('Could not write like!');
				debug($likes);
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Write dislikes to data source
	 * 
	 * @param array $dislikes
	 * @return boolean
	 */
	public function writeDislikes($dislikes=array()) {
		foreach ($dislikes as $dislike) {
			if (!$this->writePreference($dislike, DISLIKE)) {
				$this->log('Could not write dislike!');
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Write the preferences to the datasource.
	 * 
	 * @param $preference: activity_group_id
	 * @param $unwantedness: numeric weight of unwantedness
	 * 
	 * @return boolean (success)
	 */
	public function writePreference($preference, $unwantedness) {
		$this->create();
		$data = $this->createNewPreference($preference, $unwantedness);
		debug($data);
		return $this->save($data);
	}
	
	public function createNewPreference($preference, $unwantedness) {
		debug($preference);
		$course_id = $this->getCourseFromActivityId(trim($preference, '[\']'));
		debug($course_id);
		$student_group_id = $this->StudentGroup->Course->getStudentsGroupIdForThisCourse($course_id);
		return array(
			'student_group_id' => $student_group_id,
			'activity_id' => trim($preference, '[\']'),
			'unwantedness' => trim($unwantedness, '[\']')
		);
	}
	
	/**
	 * Get the course for this activity.
	 */
	public function getCourseFromActivityId($activity_id) {
		if ($course_id = $this->Activity->getCourseIdFromId($activity_id)) {
			return $course_id;
		} else {
			return false;
		}
	}
	
	/**
	 * Get students_group id on a specific course for a logged in student.
	 * 
	 * @param $course_id
	 * 
	 * @return mixed students_group_id | false
	 */
	public function getStudentsGroupIdForThisCourse($course_id) {
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
	
	
}
?>