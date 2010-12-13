<?php
class SolutionsController extends AppController {

	var $name = 'Solutions';

	function index() {
		$this->Solution->recursive = 0;
		$this->set('solutions', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid solution', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('solution', $this->Solution->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Solution->create();
			if ($this->Solution->save($this->data)) {
				$this->Session->setFlash(__('The solution has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The solution could not be saved. Please, try again.', true));
			}
		}
		$courses = $this->Solution->Course->find('list');
		$students = $this->Solution->Student->find('list');
		$activities = $this->Solution->Activity->find('list');
		$this->set(compact('courses', 'students', 'activities'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid solution', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Solution->save($this->data)) {
				$this->Session->setFlash(__('The solution has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The solution could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Solution->read(null, $id);
		}
		$courses = $this->Solution->Course->find('list');
		$students = $this->Solution->Student->find('list');
		$activities = $this->Solution->Activity->find('list');
		$this->set(compact('courses', 'students', 'activities'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for solution', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Solution->delete($id)) {
			$this->Session->setFlash(__('Solution deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Solution was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>