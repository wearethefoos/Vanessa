<?php
class CoursesController extends AppController {

	var $name = 'Courses';
	var $components = array('LdapLookup');
	
	function index() {
		$this->set('courses', $this->Course->User->getUsersCourses($this->Session->read('Auth.User.id'), $this->Session->read('Auth.User.student_id')));
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
			if (isset($this->data['Course']['password'])) {
				$students_not_found = array();
				$students = explode("\n", $this->data['Course']['students']);
				foreach ($students as $student) {
					$student = trim($student);
					$student_data = $this->Course->User->find('first', array(
						'conditions' => array('username' => $student),
						'contain' => array('Role', 'Student')
						));
					if (isset($student_data['Student']['id']) && $student_data['Student']['id']) {
						$student_id = $student_data['Student']['id'];
					} elseif ($student_data) {
						$new_student = array(
							'Student' => array(
								'coll_kaart' => $student,
								'ldap_uid'   => $student,
							));
						$this->Course->User->Student->create();
						$this->Course->User->Student->save($new_student);
						$student_id = $this->Course->User->Student->id;
					}
					
					if (!$student_data) {
						$result_lookup = $this->LdapLookup->find(
							$uvanetid = $this->Session->read('Auth.User.username'),
							$password = $this->data['Course']['password'],
							$lookup   = $student
							);
                  if ($result_lookup === false)  { // Could not connect to LDAP
					 $this->log('Could not connect to LDAP');
                     break;
                  } else if ($result_lookup == -1) { // User not found in LDAP
						$this->log(sprintf('Student %s not found.', $student));
   						$students_not_found[] = $student;
                  } else {
                     $student_data = $result_lookup;
							$new_student = array(
								'Student' => array(
									'coll_kaart' => $student,
									'ldap_uid'   => $student,
								));
							$this->Course->User->Student->create();
							$this->Course->User->Student->save($new_student);
							$student_id = $this->Course->User->Student->id;
							
							/* set extra vars for user account */
							$student_data['User']['password'] = 'password';
							$student_data['User']['student_id'] = $student_id;
							$student_data['User']['username'] = $student;
							$student_data['User']['activated'] = 1;
							$student_data['User']['role_id'] = STUDENT;
							$this->Course->User->create();
							$this->Course->User->save($student_data);
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
			$this->set('studentsnotfound', $students_not_found);
			} else {
				$this->Session->setFlash(__('Please enter your password', true), array('flash/modal'), array('class' => 'error'));
			}
		} else {
			if (!$course_id) {
				$this->Session->setFlash(__('Please select a course first!', true), 'flash/modal', array('class' => 'error'));
				$this->redirect(array('action' => 'index', 'admin' => false));
			}
			$this->data = $this->Course->read(null, $course_id);
		}

      $students = $this->Course->find('first', array(
         'conditions' => array(
            'id' => $course_id
            ),
         'contain' => array(
            'Student.User'
            )
         ));
		$students = (isset($students['Student']) && count($students['Student'])) ? $students['Student'] : array();
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
}
?>