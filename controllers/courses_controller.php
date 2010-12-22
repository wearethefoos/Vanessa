<?php
class CoursesController extends AppController {

	var $name = 'Courses';
   var $uses = array('Course', 'Email', 'User');
	var $components = array('LdapLookup');
	
	function index() {
		$this->set('courses', $this->Course->Supervisor->getUsersCourses($this->Session->read('Auth.User.id'), $this->Session->read('Auth.User.student_id')));
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
						if (!$this->Course->StudentsCourse->find('first', array(
							'conditions' => array(
								'student_id' => $student_id,
								'course_id'  => $this->data['Course']['id']
								)
							))) {
							$this->Course->StudentsCourse->create();
							$this->Course->StudentsCourse->save(array(
								'StudentsCourse' => array(
									'student_id' => $student_id,
									'course_id'  => $course_id,
								)));
						}
					}
				}
            if (count($students_not_found) > 0) {
               $this->Session->setFlash(__('One or more UvAnetID\'s not found', true), 'flash/modal', array('class' => 'error'));
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
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'course'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('course', $this->Course->read(null, $id));
	}

	function admin_add() {
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
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'course'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Course->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'course'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'course'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Course->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'course'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Course->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Course'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Course'));
		$this->redirect(array('action' => 'index'));
	}

   function admin_delete_invite($course_id, $student_id) {
		if (!$course_id && !$student_id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), ($course_id ? 'student' : 'course')));
			$this->redirect(array('action' => 'index'));
		}
      $this->Course->StudentsCourse->deleteAll(array(
         'StudentsCourse.student_id' => $student_id,
         'StudentsCourse.course_id'  => $course_id
         ));
      $this->redirect(array('action' => 'admin_invite/' . $course_id));
   }

   function admin_send_email_invitation($course_id) {
		if (!$course_id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'course'));
			$this->redirect(array('action' => 'index'));
		}
      $course = $this->Course->findCourse($course_id);
      $students = $course['Student'];

      $course_name = $course['Course']['name'];
      $supervisor = $course['Supervisor'];

      foreach ($students as $student) {
         $this->Email->sendCourseInvitation($student, $course_name, $supervisor);
      }

      $this->redirect(array('action' => 'admin_invite/' . $course_id));
   }
}
?>