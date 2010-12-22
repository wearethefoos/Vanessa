<?php
class PreferencesController extends AppController {

	var $name = 'Preferences';
	
	function add($course_id=null) {
		if (!$course_id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'course'));
			$this->redirect(array('/'));
		}
		
		$likes = $this->params['form']['prefs'];
		$dislikes = $this->params['form']['noprefs'];
		
		$likes = trim($likes, '[]');
		$dislikes = trim($dislikes, '[]');
		
		$likes = explode(',', $likes);
		$dislikes = explode(',', $dislikes);
		
		debug($likes);
		debug($dislikes);
		
		if ($this->Preference->savePreferences($likes, $dislikes)) {
			$this->Session->setFlash(__('Your cherries are now save! :)', true), 'message/flash', array('class' => 'success'));
		} else {
			$this->Session->setFlash(__('Your cherries could not be saved! :( Plz try again!', true), 'message/flash', array('class' => 'error'));
		}
		
		$this->redirect(array(
			'controller' => 'courses',
			'action' => 'pick',
			$course_id,
		));
	}

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