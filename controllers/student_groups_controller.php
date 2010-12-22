<?php
/**
 * StudentGroupsController
 * 
 * [Short Description]
 *
 * @package default
 * @author Wouter de Vos
 * @version $Id$
 * @copyright SureCreations
 **/

class StudentGroupsController extends AppController {
/**
 * The name of this controller. Controller names are plural, named after the model they manipulate.
 *
 * @var string
 * @access public
 */
	var $name = 'StudentGroups';

/**
 * Array containing the names of components this controller uses. Component names
 * should not contain the "Component" portion of the classname.
 *
 * @var array
 * @access public
 */
	var $components = array('LdapLookup');
	
	
/**
 * This is the the first step in choosing activities: Choosing the group.
 */
	function choose($course_id=null) {
		if (!$course_id) {
			$this->Session->setFlash(__('Whops! That is not a valid course!', true), 'flash/modal', array('class' => 'error'));
			$this->redirect('/');
		}
		
		if ($group_id = $this->StudentGroup->createGroupForCourse($course_id)) {
			$preferences = array();
			if ($grouped = $this->StudentGroup->checkIfStudentIsGrouped($course_id)) {
				$preferences = $this->StudentGroup->getPreferences($course_id);
			}
		} else {
			$this->Session->setFlash(__('Whoah! Something went a little wrong there.. Try again, and make sure you are a student :)', true), 'flash/modal', array('class' => 'error'));
			$this->redirect('/');
		}
		
		if ($this->data) {
			if (isset($this->data['StudentGroup']['group_up']) &&
				$this->data['StudentGroup']['group_up'] == 0
			) {
				if ($grouped) {
					/* withdraw from group and create a new one */
					$this->StudentGroup->withdrawFromGroup($group_id);
					$this->StudentGroup->createGroupForCourse($course_id);
				}
				$this->redirect(array('controller' => 'courses', 'action' => 'pick', $course_id));
			}
		}
		
		
		$this->set(compact('grouped', 'preferences', 'course_id'));
	}

}
?>