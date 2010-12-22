<?php
/**
 * Short description for file.
 *
 * The user model.
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
 * Users are ACL requesters, although most of the ACL is managed through their parent Role.
 * 
 * There are two types of Users: those imported from the LDAP, and those created in VANESSA.
 *
 * @package       cake
 * @subpackage    cake.vanessa
 * @link		  https://knack.fmg.uva.nl/trac/april/browser/trunk/va/models/user.php
 * @author		  W.R.deVos@uva.nl
 */
class User extends AppModel {
	var $name = 'User';
	var $actsAs = array('Acl' => 'requester');
	var $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
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

	var $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'SecurityLog' => array(
			'className' => 'SecurityLog',
			'foreignKey' => 'user_id',
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

/**
 * Gets all the courses related to this user, given that
 * it is a student. (Needed for the dashboard overview)
 *
 * @param numeric $student: the id of the student.
 *
 */
	function getUsersCourses($user_id = null, $student_id=null, $list=false) {
	    if ($student_id) {
	        $conditions = array(
	            'joins' => array(
                        array(
                            'table' => 'students_courses',
                            'alias' => 'StudentsCourse',
                            'type' => 'inner',
                            'conditions' => array(
                                'StudentsCourse.course_id = Course.id'
                            )
                        ),
	            ),
	            'conditions' => array(
	                'StudentsCourse.student_id' => $student_id
	            ),
	        );
			
	    } else {
			$conditions = array(
				'conditions' => array(
					'Course.user_id' => $user_id,
					),
				'contain' => array(
					'Activity'
					)
				);
		}
		if ($list)
			return $this->Student->Course->find('list', $conditions);
			
        return $this->Student->Course->find('all', $conditions);
	}

/**
 * Gets all the courses related to this user, given that
 * it is a student. (Needed for the dashboard overview)
 * 
 * @param int $student: the id of the student.
 *
 */	
	function getStudentsUnpreferenced() {
	    if ($courses = $this->getStudentsCourses()) {
						
			foreach ($courses as $course_id => $course_name) {
				if ($this->Student->Course->getStudentsGroupIdForThisCourse($course_id)) {
					unset ($courses[$course_id]);
				}
			}
			
			return $courses;
	    }
	    return false;
	}
/**
 * Gets all the courses related to this user, given that
 * it is a student. (Needed for the dashboard overview)
 *
 * @param numeric $student: the id of the student.
 *
 */
	function getStudentsPlacements() {
	    if ($student = $this->getStudentIdFromSession()) {
			return $this->Student->Solution->find('all', array(
				'conditions' => array(
					'student_id' => $student,
				)
			));
	    }
	    return false;
	}

/**
 * Gets all the courses related to this user, given that
 * it is a student. (Needed for the dashboard overview)
 *
 * @param numeric $student: the id of the student.
 *
 */
	function getStudentsUnassigned($student=null) {
	    if ($courses = $this->getStudentsCourses(true)) {
			$courses = array_keys($courses);
			
		}
	    return false;
	}
	
/**
 * Gets all the courses related to this user, given that
 * it is a student. (Needed for the dashboard overview)
 * 
 * @param boolean $list: return a list (true) or full dataset [default]
 */
	function getStudentsCourses($list=false) {
		if ($student = $this->getStudentIdFromSession()) {
			
			$conditions = array(
				'joins' => array(
					array(
						'table' => 'students_courses',
						'alias' => 'StudentsCourse',
						'type' => 'inner',
						'conditions' => array(
							'StudentsCourse.student_id' => $student,		
						)
					)
				),
	        );
			if ($list) {
				$conditions['fields'] = array('Course.id', 'Course.name'); 
			}
			$list = ($list) ? 'list' : 'all';
		
	        $courses = $this->Student->Course->find($list, $conditions);
	
			return $courses;
	    }
	    return false;
	}

/**
 * Generates pronouncable passwords.
 *
 * @param $length integer: length of the generated password.
 * @return string: the generated password.
 */
	function generatePassword($length=10){
        $password = '';
        srand((double)microtime()*1000000);
        
        $vowels = array("a", "e", "i", "o", "u");
        $cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
        "cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl");
        
        $num_vowels = count($vowels);
        $num_cons = count($cons);
        
        for($i = 0; $i < $length; $i++){
            $password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];
        }
        
        return substr($password, 0, $length);
    }
	
    function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        $data = $this->data;
        if (empty($this->data)) {
            $data = $this->read();
        }
        if (empty($data['User']['role_id'])) {
            return null;
        } else {
            return array('Role' => array('id' => $data['User']['role_id']));
        }
    }

	public function afterFind($results, $primary) {
		if (!$primary) {
			if (isset($results['name'])) {
				$results['name'] = utf8_encode($results['name']);
			}
		} else {
			for ($i=0; $i<count($results); $i++) {
				$results[$i]['User']['name'] = utf8_encode($results[$i]['User']['name']);
			}
		}
		return $results;
	}
    
/**    
 * After save callback
 *
 * Update the aro for the user.
 *
 * @access public
 * @return void
 */
    function afterSave($created) {
            if (!$created) {
                $parent = $this->parentNode();
                $parent = $this->node($parent);
                $node = $this->node();
                $aro = $node[0];
                $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
                $this->Aro->save($aro);
            }
    }

}
?>