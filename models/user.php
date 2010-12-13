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
 * @param numeric $student: the id of the student.
 *
 */	
	function getStudentsUnpreferenced($student=null) {
	    if ($student) {
	        $excluded = $this->Student->Course->find('list',
	            array(
	                'fields' => array(
	                    'Course.id',
	                    'Course.name',
	                ),
	                'joins' => array(
        	            array(
        	                'table' => 'students_courses',
        	                'alias' => 'StudentsCourses',
        	                'type' => 'INNER',
        	                'conditions' => array(
                                    'StudentsCourses.course_id = Course.id'
        	                )
        	            ),
        	            array(
        	                'table' => 'students',
        	                'alias' => 'Student',
        	                'type' => 'INNER',
        	                'conditions' => array(
        	                    'StudentsCourses.student_id = Student.id',
        	                )
        	            ),
        	            array(
        	                'table' => 'students_preferences',
        	                'alias' => 'StudentsPreferences',
        	                'type' => 'LEFT',
        	                'conditions' => array(
        	                    'StudentsPreferences.student_id = Student.id',
        	                )
        	            ),
        	            array(
        	                'table' => 'activities',
        	                'alias' => 'Activity',
        	                'type' => 'INNER',
        	                'conditions' => array(
                                    'Activity.course_id = Course.id'
        	                )
        	            ),
	                ),
	            	'conditions' => array(
	                     'Student.id' => $student,
	                ),
	            )
	        );

                $included = $this->Student->Course->find('list',
	            array(
	                'fields' => array(
	                    'Course.id',
	                    'Course.name',
	                ),
	                'joins' => array(
        	            array(
        	                'table' => 'students_courses',
        	                'alias' => 'StudentsCourses',
        	                'type' => 'INNER',
        	                'conditions' => array(
                                    'StudentsCourses.course_id = Course.id'
        	                )
        	            ),
        	            array(
        	                'table' => 'students',
        	                'alias' => 'Student',
        	                'type' => 'INNER',
        	                'conditions' => array(
        	                    'StudentsCourses.student_id = Student.id',
        	                )
        	            ),
        	            array(
        	                'table' => 'students_preferences',
        	                'alias' => 'StudentsPreferences',
        	                'type' => 'INNER',
        	                'conditions' => array(
        	                    'StudentsPreferences.student_id = Student.id',
        	                )
        	            ),
        	            array(
        	                'table' => 'activities',
        	                'alias' => 'Activity',
        	                'type' => 'INNER',
        	                'conditions' => array(
        	                    'StudentsPreferences.activity_id = Activity.id',
                                    'Activity.course_id = Course.id'
        	                )
        	            ),
	                ),
	            	'conditions' => array(
	                     'Student.id' => $student,
	                ),
	            )
	        );
                return array_diff($excluded, $included);
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
	function getStudentsPlacements($student=null) {
	    if ($student) {
	        $conditions = array(
	            'fields' => array(
	                'DISTINCT Course.id',
	        	'Course.name',
	                'Course.description',
	                'Solution.persistant',
                        'Activity.*'
	            ),
	            'joins' => array(
    	            array(
    	                'table' => 'students_courses',
    	                'alias' => 'StudentsCourse',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'StudentsCourse.course_id = Course.id'
    	                )
    	            ),
    	            array(
    	                'table' => 'students',
    	                'alias' => 'Student',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'Student.id = StudentsCourse.student_id'
    	                )
    	            ),
    	            array(
    	                'table' => 'activities',
    	                'alias' => 'Activity',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'Activity.course_id = Course.id'
    	                )
    	            ),
    	            array(
    	                'table' => 'placements',
    	                'alias' => 'Placement',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'Placement.activity_id = Activity.id'
    	                )
    	            ),
    	            array(
    	                'table' => 'solutions',
    	                'alias' => 'Solution',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'Placement.solution_id = Solution.id'
    	                )
    	            ),
	            ),
	            'conditions' => array(
	                'StudentsCourse.student_id' => $student,
	                'Solution.persistant' => 1
	            ),
	        );
			$conditions = array(
				'contain' => array(
					'Student' => array(
						'conditions' => array(
							'Student.id' => $student,
						)),
					'Activity',
					'Activity.Placement',
					'Activity.Placement.Solution' => array(
						'conditions' => array(
							'Solution.persistant' => 1
						)),
					),
				);
				
			$placements = array();
	        $courses = $this->Student->Course->find('all', $conditions);
			foreach ($courses as $course_key => $course) {
				if (count($course['Activity'])) {
					foreach ($course['Activity'] as $activity_key => $activity) {
						if (count($activity['Placement']) && count($activity['Placement'][0]['Solution'])) {
							$placements[$course_key] = array(
								'Course' => $course['Course'],
								'Activity' => $course['Activity'][$activity_key],
							);
						}
					}
				}
			}
			return $placements;
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
	    if ($student) {
	        $conditions = array(
	            'list' => array(
	                'Course.id',
	        	'Course.name',
	            ),
	            'joins' => array(
    	            array(
    	                'table' => 'students_courses',
    	                'alias' => 'StudentsCourse',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'StudentsCourse.course_id = Course.id'
    	                )
    	            ),
    	            array(
    	                'table' => 'students',
    	                'alias' => 'Student',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'Student.id = StudentsCourse.student_id'
    	                )
    	            ),
    	            array(
    	                'table' => 'activities',
    	                'alias' => 'Activity',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'Activity.course_id = Course.id'
    	                )
    	            ),
    	            array(
    	                'table' => 'placements',
    	                'alias' => 'Placement',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'Placement.activity_id = Activity.id'
    	                )
    	            ),
    	            array(
    	                'table' => 'solutions',
    	                'alias' => 'Solution',
    	                'type' => 'inner',
    	                'conditions' => array(
    	                    'Placement.solution_id = Solution.id'
    	                )
    	            ),
	            ),
	            'conditions' => array(
	                'StudentsCourse.student_id' => $student,
	                'Solution.persistant' => 1
	            ),
	        );
	        $assigned = $this->Student->Course->find('list', $conditions);
	        $all = $this->getUsersCourses($student, true);
                return array_diff($all, $assigned);
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