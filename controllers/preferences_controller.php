<?php
class PreferencesController extends AppController {

	var $name = 'Preferences';
	
	
	function view($id=null) {}
	function edit($id=null) {}
	function delete($id=null) {}

	function admin_index() {
		$this->Preference->recursive = 0;
		$this->set('preferences', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'preference'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('preference', $this->Preference->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Preference->create();
			if ($this->Preference->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'preference'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'preference'));
			}
		}
		$students = $this->Preference->Student->find('list');
		$this->set(compact('students'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'preference'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Preference->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'preference'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'preference'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Preference->read(null, $id);
		}
		$students = $this->Preference->Student->find('list');
		$this->set(compact('students'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'preference'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Preference->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Preference'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Preference'));
		$this->redirect(array('action' => 'index'));
	}
}
?>