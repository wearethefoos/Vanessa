<?php
class ActivitiesController extends AppController {

	var $name = 'Activities';

	function index() {
		$this->Activity->recursive = 0;
		$this->set('activities', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid activity', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('activity', $this->Activity->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Activity->create();
			if ($this->Activity->save($this->data)) {
				$this->Session->setFlash(__('The activity has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The activity could not be saved. Please, try again.', true));
			}
		}
		$courses = $this->Activity->Course->find('list');
		$this->set(compact('courses'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid activity', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Activity->save($this->data)) {
				$this->Session->setFlash(__('The activity has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The activity could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Activity->read(null, $id);
		}
		$courses = $this->Activity->Course->find('list');
		$this->set(compact('courses'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for activity', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Activity->delete($id)) {
			$this->Session->setFlash(__('Activity deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Activity was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>