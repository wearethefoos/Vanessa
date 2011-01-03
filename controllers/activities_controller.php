<?php
class ActivitiesController extends AppController {

	var $name = 'Activities';
	
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'activity'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('activity', $this->Activity->read(null, $id));
	}

	function admin_index() {
		$this->Activity->recursive = 0;
		$this->set('activities', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'activity'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('activity', $this->Activity->read(null, $id));
	}

	function admin_add($course_id=null) {
		$this->layout = 'large';
		if (!empty($this->data)) {
			$this->Activity->create();
			if ($this->Activity->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'activity'), 'flash/modal', array('class' => 'success'));
				//$this->redirect(array('controller' => 'courses', 'action' => 'view', $this->data['Activity']['course_id'], 'admin' => false));
				$course_id = $this->data['Activity']['course_id'];
				$this->data = null;
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'activity'), 'flash/modal', array('class' => 'error'));
			}
		}
		if ($course_id) {
			$this->data['Activity']['course_id'] = $course_id;
		}
		$this->set('courses', $this->Activity->Course->fromUserWithId($this->Session->read('Auth.User.id'), true));
		$this->set('activities', $this->Activity->getActivityListFromCourse($course_id));
	}

	function admin_edit($id = null) {
      $this->layout = 'large';
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'activity'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Activity->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'activity'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'activity'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Activity->read(null, $id);
		}
		$this->set('courses', $this->Activity->Course->fromUserWithId($this->Session->read('Auth.User.id'), true));
	}

	function admin_delete($course_id, $activity_id = null) {
		if (!$activity_id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'activity'), 'flash/modal', array('class' => 'error'));
		} else {
         if ($this->Activity->delete($activity_id)) {
            $this->Session->setFlash(sprintf(__('%s deleted', true), 'Activity'), 'flash/modal', array('class' => 'success'));
   		} else {
            $this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Activity'), 'flash/modal', array('class' => 'error'));
         }
      }
      $this->redirect(array('controller' => 'courses','action'=>'view', $course_id, 'admin' => true));
	}
}
?>