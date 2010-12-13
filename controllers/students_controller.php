<?php
class StudentsController extends AppController {

	var $name = 'Students';

	function admin_index() {
		$this->Student->recursive = 0;
		$this->set('students', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'student'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('student', $this->Student->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Student->create();
			if ($this->Student->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'student'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'student'));
			}
		}
		$preferences = $this->Student->Preference->find('list');
		$this->set(compact('preferences'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'student'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Student->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'student'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'student'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Student->read(null, $id);
		}
		$preferences = $this->Student->Preference->find('list');
		$this->set(compact('preferences'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'student'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Student->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Student'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Student'));
		$this->redirect(array('action' => 'index'));
	}
}
?>