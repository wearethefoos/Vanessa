<?php
class CoursesController extends AppController {

	var $name = 'Courses';
   var $uses = array('Course', 'Email', 'User');
	var $components = array('LdapLookup');

	function index() {
      $student_id = null;
      if ($this->Session->read('Auth.User.role_id') == STUDENT)
         $student_id = $this->Session->read('Auth.User.student_id');
		$this->set('courses', $this->User->getUsersCourses($this->Session->read('Auth.User.id'), $student_id));
	}
	
	function view($id) {
		$this->layout = 'large';
		
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'course'));
			$this->redirect(array('action' => 'index'));
		}
		$course = $this->Course->read(null, $id);
		if ($this->Session->read('Auth.User.role_id') == STUDENT) {
			$preferences = $this->Course->getStudentsPreferences($id, $this->Session->read('Auth.User.student_id'));
		}
		$this->set(compact('course', 'preferences'));
	}
	
	function pick($id=null) {
		$this->layout = 'large';
	    if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'course'));
			$this->redirect(array('action' => 'index'));
		}
		
		$preferences = $this->Course->getStudentsPreferences($id);
		
		$this->set('preferences', $preferences);
		$this->set('course', $this->Course->read(null, $id));
	}

	function admin_index() {
		$this->Course->recursive = 0;
		$this->set('courses', $this->paginate(array(
				'Course.user_id' => $this->Session->read('Auth.User.id')
			)));
	}


	function admin_invite($course_id=null) {
		$this->layout = 'large';
		
		if ($course_id || isset($this->data['Course']['id'])) {
			$course_id = ($course_id) ? $course_id : $this->data['Course']['id'];
		}
		if (!empty($this->data)) {
         $wrong_password = false;
         $students_not_found = array();
			if (isset($this->data['Course']['password'])) {
				$students = explode("\n", $this->data['Course']['students']);
				foreach ($students as $uvanetid) {
					$uvanetid = preg_replace('/[\W]*$/', '', $uvanetid);
               $uvanetid = preg_replace('/^[\W]*/', '', $uvanetid);
               if (empty($uvanetid)) {
                  continue;
               }
               // Can we find this uvanetid in the user table
					$student_data = $this->User->find('first', array(
						'conditions' => array('username' => $uvanetid),
						'contain' => array('Role', 'Student')
						));
               // If this user exists, check whether it is also in the student table, if not add it
               if ($student_data) {
                  if (isset($student_data['Student']['id']) && $student_data['Student']['id']) {
                     $student_id = $student_data['Student']['id'];
                  } else {
                     $new_student = array(
                        'Student' => array(
                           'coll_kaart' => $uvanetid,
                           'ldap_uid'   => $uvanetid,
                        ));
                     $this->User->Student->create();
                     $this->User->Student->save($new_student);
                     $student_id = $this->User->Student->id;
                  }
               } else {
                  // This user is not in the database yet.
                  // Check whether it exists in LDAP
						$result_lookup = $this->LdapLookup->find(
							$this->Session->read('Auth.User.username'),
							$this->data['Course']['password'],
							$uvanetid
							);
                  if ($result_lookup === false)  { // Could not connect to LDAP
                     $wrong_password = true;
                     break;
                  } else if ($result_lookup == -1) { // User not found in LDAP
   						$students_not_found[] = $uvanetid;
                  } else {
                     $student_data = $result_lookup;
							$new_student = array(
								'Student' => array(
									'coll_kaart' => $uvanetid,
									'ldap_uid'   => $uvanetid,
								));
							$this->User->Student->create();
							$this->User->Student->save($new_student);
							$student_id = $this->User->Student->id;
							
							/* set extra vars for user account */
							$student_data['User']['password'] = 'password';
							$student_data['User']['student_id'] = $student_id;
							$student_data['User']['username'] = $uvanetid;
							$student_data['User']['activated'] = 1;
							$student_data['User']['role_id'] = STUDENT;
							$this->User->create();
							$this->User->save($student_data);
						}
					}

					if ($student_data) {
						/* add student to this course -- if necessary */
                  if (!$this->Course->isStudentInCourse($this->data['Course']['id'], $student_id)) {
							$this->Course->Group->create();
							$this->Course->Group->save(array(
								'Group' => array(
									'course_id'  => $course_id,
                           ),
                        'Student' => array('Student' => array($student_id))));

						}
					}
				}
            if (count($students_not_found) > 0) {
               $this->Session->setFlash(__("One or more UvAnetID\'s not found", true), 'flash/modal', array('class' => 'error'));
            }
			} else { // No password has been given
            $wrong_password = true;
				$this->Session->setFlash(__('Please enter your password', true), array('flash/modal'), array('class' => 'error'));
			}

         if ($wrong_password) {
            $students_not_found = array();
            $students = explode("\n", $this->data['Course']['students']);
            foreach ($students as $uvanetid) {
					$uvanetid = preg_replace('/[\W]*$/', '', $uvanetid);
               $uvanetid = preg_replace('/^[\W]*/', '', $uvanetid);
               $students_not_found[] = $uvanetid;
            }

         }
         $this->set('studentsnotfound', $students_not_found);
		} else {
			if (!$course_id) {
				$this->Session->setFlash(__('Please select a course first!', true), 'flash/modal', array('class' => 'error'));
				$this->redirect(array('action' => 'index', 'admin' => false));
			}
			$this->data = $this->Course->read(null, $course_id);
		}

      $students = $this->Course->findStudents($course_id);

      $this->set(compact('students'));

	}

	function admin_view($id = null) {
      $this->layout = 'large';
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'course'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('course', $this->Course->read(null, $id));
	}

	function admin_add() {
      $this->layout = 'large';
		if (!empty($this->data)) {
			$this->data['Course']['user_id'] = $this->Session->read('Auth.User.id');
			$this->Course->create();
			if ($this->Course->save($this->data)) {
				$this->Session->setFlash(sprintf(__('Excelent! Now add some activities to the %s', true), 'course'), 'flash/modal', array('class' => 'success'));
				$this->redirect(array('controller' => 'activities', 'action' => 'add', $this->Course->id, 'admin' => true));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'course'), 'flash/modal', array('class' => 'error'));
			}
		}
	}

	function admin_edit($id = null) {
      $this->layout = 'large';
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'course'), 'flash/modal', array('class' => 'error'));
			$this->redirect(array('action' => 'index', 'admin' => false));
		}
		if (!empty($this->data)) {
			if ($this->Course->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'course'), 'flash/modal', array('class' => 'success'));
				$this->redirect(array('action' => 'index', 'admin' => false));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'course'), 'flash/modal', array('class' => 'error'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Course->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true)), 'flash/modal', array('class' => 'error'));
			$this->redirect(array('action'=>'index', 'admin' => true));
		}
		if ($this->Course->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true)), 'flash/modal', array('class' => 'success'));
			$this->redirect(array('action'=>'index', 'admin' => true));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true)), 'flash/modal', array('class' => 'error'));
		$this->redirect(array('action' => 'index', 'admin' => true));
	}

   function admin_delete_invite($course_id, $student_id) {
		if (!$course_id || $student_id == null) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), (!$course_id ? 'course' : 'student')), 'flash/modal', array('class' => 'error'));
			$this->redirect(array('action' => 'index', 'admin' => true));
		}
      $student_ids = explode('_', $student_id);
      $conditions = array('StudentsCourse.course_id'  => $course_id, 'StudentsCourse.student_id' => $student_ids);
      $this->Course->StudentsCourse->deleteAll($conditions);
      $this->redirect(array('action' => 'invite', $course_id, 'admin' => true));
   }

   function admin_send_invitation_emails($course_id) {
		$this->layout = 'js/json';

      $message = '';
      $type = 'success';
		if (!$course_id) {
         $message = __('Wrong course id', true);
         $type = 'error';
		} else {
         $course = $this->Course->findCourse($course_id);
         $students = $course['Student'];

         $course_name = $course['Course']['name'];
         $supervisor = $course['Supervisor'];

         foreach ($students as $student) {
            $this->Email->sendCourseInvitation($student, $course_name, $supervisor);
         }
         $message = __('Mails to students are successfully send!', true);
      }

      $result = array('message' => $message, 'type' => $type);
      $this->set('json', $result);
   }

   function admin_roster($course_id = null) {
		$this->layout = 'blank';
      require '..\vendors\place_students.php';
      if ($course_id == null) {
         $placements = new PlaceStudents(null, array(
                                                'nb_activities' => 50,
                                                'nb_students' => 550,
                                                'nb_groups' => 100,
                                                'nb_preferences'=> 7
                           ));
      } else {
         App::import('Model', 'Activity');
         App::import('Model', 'Course');
         App::import('Model', 'Group');

         $activity_model = new Activity();
         $course_model = new Course();
         $group_model = new Group();

         $data = array();

         $data['activities'] = $activity_model->getActivityListFromCourse($course_id);
         $data['students'] = $course_model->getStudentList($course_id);
         $data['student_groups'] = $group_model->getStudentGroupListFromCourse($course_id);

         $placements = new PlaceStudents($data);
      }
      $result = $placements->find_best_fit();
      
      $groups = array();
      foreach($placements->student_groups as $group_id => $group) {
         $new_group = array();
         foreach($group['group'] as $student_id) {
            $new_group[] = $placements->students[$student_id]['number'];
         }
         sort($new_group);
         $groups[$group_id] = implode('-', $new_group);
      }
      $activities = array();
      foreach($placements->activities as $activity_id => $activity) {
         $activities[$activity_id] = $activity['name'];
      }
      asort($groups);

      $this->set(compact('result', 'placements', 'groups', 'activities'));
   }

}
?>