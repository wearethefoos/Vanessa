<?php
class SecurityLogsController extends AppController {

	var $name = 'SecurityLogs';

	function admin_index() {
		$this->SecurityLog->recursive = 0;
		$this->set('securityLogs', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'security log'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('securityLog', $this->SecurityLog->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->SecurityLog->create();
			if ($this->SecurityLog->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'security log'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'security log'));
			}
		}
		$users = $this->SecurityLog->User->find('list');
		$this->set(compact('users'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'security log'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->SecurityLog->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'security log'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'security log'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->SecurityLog->read(null, $id);
		}
		$users = $this->SecurityLog->User->find('list');
		$this->set(compact('users'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'security log'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->SecurityLog->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Security log'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Security log'));
		$this->redirect(array('action' => 'index'));
	}
}
?>