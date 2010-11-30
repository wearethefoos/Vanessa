<?php
class SecurityLogsController extends AppController {

	var $name = 'SecurityLogs';

	function index() {
		$this->SecurityLog->recursive = 0;
		$this->set('securityLogs', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid security log', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('securityLog', $this->SecurityLog->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->SecurityLog->create();
			if ($this->SecurityLog->save($this->data)) {
				$this->Session->setFlash(__('The security log has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The security log could not be saved. Please, try again.', true));
			}
		}
		$users = $this->SecurityLog->User->find('list');
		$this->set(compact('users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid security log', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->SecurityLog->save($this->data)) {
				$this->Session->setFlash(__('The security log has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The security log could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->SecurityLog->read(null, $id);
		}
		$users = $this->SecurityLog->User->find('list');
		$this->set(compact('users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for security log', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->SecurityLog->delete($id)) {
			$this->Session->setFlash(__('Security log deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Security log was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>