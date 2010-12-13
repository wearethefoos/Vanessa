<?php
class UsersController extends AppController {

	var $name = 'Users';
	
    function beforeFilter() {
        parent::beforeFilter();
        //$this->LdapAuth->allow('*'); // TODO: remove?
        //$this->LdapAuth->allow(array('login', 'logout', 'register'));
    }
    
    function register() {
        $this->autoRender = false;
        $this->data['User'] = $this->Session->read('user_data');
        if ($this->LdapAuth->_loggedIn && $this->data['User']) {
            if ($this->User->findByUsername($this->data['User']['username'])) {
                $this->Session->setFlash(__('You are already registered!', true), 'flash/modal', array('class' => 'error'));
                $this->redirect('/login');
            } else {
                $this->data = $data;
            }
        } 
        if (!empty($this->data)) {
            $this->User->create();
            $this->data['User']['activated'] = 1;
            if ($this->User->save($this->data)) {
                $logindata['User.username'] = $this->data['User']['username'];
                $logindata['User.password'] = $this->Session->read('Temp.password');
                $this->Session->delete('Temp.password');
                if ($this->data['User']['role_id'] == 7) { // student
					$studentExists = $this->User->Student->findByLdapUid($this->data['User']['username']);
					if ($studentExists) {
						$this->User->Student->id = $studentExists['Student']['id'];
					} else {
	                    $this->User->Student->create();
	                    $this->User->Student->save(array(
	                        'Student' => array(
	                            'coll_kaart' => $this->data['User']['username'],
	                            'ldap_uid' => $this->data['User']['username'],
	                        )
	                    ));
					}
                    $this->User->save(array(
                        'User' => array(
                            'id' => $this->User->id,
                            'student_id' => $this->User->Student->id,
                        )
                    ));
                }
                if (!$this->LdapAuth->login($logindata)) {
                    $this->Session->setFlash(__('First timer, huh? You are now registered, thank you! Please log in, now. ;)', true), 'flash/modal', array('class' => 'success'));
                    $this->redirect('/login');
                } else {
                    $this->Session->setFlash(__('First timer, huh? You are now registered, thank you! ;)', true), 'flash/modal', array('class' => 'success'));
					$this->redirect('/login');
                }
            }
        } else {
            $this->Session->setFlash(__('Please, login with your UvANetID first.', true), 'flash/modal', array('class' => 'error'));
            $this->redirect('/login');
        }
    }
	
    function login() {
    	if ($this->Session->read('Auth.User')) {
    	    if (!$this->RequestHandler->isAjax()) {
    		    $this->Session->setFlash(__('You are logged in!', true), 'flash/modal', array('class' => 'success'));
    		    $this->redirect('/dashboard', null, false);
    	    }
    	}
    	$this->layout = 'clean';
    }
    
    function dashboard() {
        $this->set('title_for_layout', __('Dashboard', true));
        $id = $this->Session->read('Auth.User.id');
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Heya! Login first, please ;)', true), 'flash/modal', array('class' => 'error'));
            $this->redirect(array('action' => 'login'));
        }
		$user_id = $this->Session->read('Auth.User.id');
        $student_id = $this->Session->read('Auth.User.student_id');
        $this->set('courses', $this->User->getUsersCourses($user_id, $student_id));
        $this->set('assignments', $this->User->getStudentsPlacements($student_id));
        $this->set('unpreferenced', $this->User->getStudentsUnpreferenced($student_id));
        $this->set('unassigned', $this->User->getStudentsUnassigned($student_id));
    }
    
    function admin_login() {
        $this->redirect(array('action' => 'login', 'admin' => false));
    }
         
    function logout() {
        $this->Session->setFlash(__('You are logged out.', true), 'flash/modal', array('class' => 'success'));
        $this->redirect($this->LdapAuth->logout());
    }
    
    function profile() {
        $id = $this->Session->read('Auth.User.id');
        if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'user'), 'flash/modal', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
		    $this_users_id = $this->User->read(null, $id);
		    $this_users_id = $this_users_id['User']['id'];
		    if ($this->data['User']['id'] != $this_users_id) {
		        $this->Session->setFlash(sprintf(__('Fraudulent action! %s not saved.', true), 'profile'), 'flash/modal', array('class' => 'error'));
		        $this->redirect('/');
		    }
		    $fieldList = array('id', 'username', 'email', 'phone_number', 'name', 'sex');
//		    if (empty($this->data['User']['password']) || $this->data['User']['password']==$this->Auth->password('')) {
//		        unset($fieldList[2]);
//		        unset($this->data['User']['password']);
//		    }
		    //debug($fieldList);
			if ($this->User->save($this->data, true, $fieldList)) {
				$this->Session->setFlash(sprintf(__('Your %s has been saved', true), 'profile'), 'flash/modal', array('class' => 'success'));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(sprintf(__('Oops! Your %s could not be saved. Please, try again.', true), 'profile'), 'flash/modal', array('class' => 'error'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		unset($this->data['User']['password']);
    }

	function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'user'), 'flash/modal', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

    function admin_add() {
		if (!empty($this->data)) {
			$this->User->create();
			if (!isset($this->data['User']['password']) || empty($this->data['User']['password'])) {
			    if ($this->Session->read('Auth.User.role_id') <= 5) {
			        $this->data['User']['password'] = $this->User->generatePassword();
			    } else {
			        $this->log('Someone is trying to register wihout a password!', 'error');
			        $this->Session->setFlash(__('You are not allowed to do that!'), 'flash/modal', array('class' => 'error'));
			        $this->redirect(array('/'));
			    }
			    $this->_sendCredentials($this->data['User']);
			}
			
			$this->data['User']['password'] = $this->LdapAuth->password($this->data['User']['password']);
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'user'), 'flash/modal', array('class' => 'success'));
				    $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'user'), 'flash/modal', array('class' => 'error'));
			}
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'user'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
		    if ($this->data['User']['password'])
		        $this->data['User']['password'] = $this->LdapAuth->password($this->data['User']['password']);
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'user'), 'flash/modal', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'user'), 'flash/modal', array('class' => 'error'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'user'), 'flash/modal', array('class' => 'error'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'User'), 'flash/modal', array('class' => 'success'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'User'), 'flash/modal', array('class' => 'error'));
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * Init the acl db
 */
    function admin_initDB() {
        $Role =&  $this->User->Role;
        
        //Admins
        $Role->id = 5;     
        $this->Acl->allow($Role, 'controllers');
     
        //Managers
        $Role->id = 6;
        $this->Acl->deny($Role, 'controllers');
        
        $this->Acl->allow($Role, 'controllers/Activities');
        $this->Acl->allow($Role, 'controllers/Placements');
        $this->Acl->allow($Role, 'controllers/Preferences');
        $this->Acl->allow($Role, 'controllers/Solutions');
        $this->Acl->allow($Role, 'controllers/Users/view');
        $this->Acl->allow($Role, 'controllers/Users/profile');
        $this->Acl->allow($Role, 'controllers/Users/logout');
        $this->Acl->allow($Role, 'controllers/Users/admin_add');
        $this->Acl->allow($Role, 'controllers/Users/admin_view');
        $this->Acl->allow($Role, 'controllers/Students');
     
        //Students
        $Role->id = 7;
        $this->Acl->deny($Role, 'controllers');
        
        $this->Acl->allow($Role, 'controllers/Activities/index');
        $this->Acl->allow($Role, 'controllers/Activities/view');        
        $this->Acl->allow($Role, 'controllers/Activities/subscribe');        
        $this->Acl->allow($Role, 'controllers/Activities/unsubscribe');  
               
        $this->Acl->allow($Role, 'controllers/Preferences/add');
        $this->Acl->allow($Role, 'controllers/Preferences/view');
        $this->Acl->allow($Role, 'controllers/Preferences/edit');
        $this->Acl->allow($Role, 'controllers/Preferences/delete');
        
        $this->Acl->allow($Role, 'controllers/Solutions/view');
        $this->Acl->allow($Role, 'controllers/Placements/index');
        
        $this->Acl->allow($Role, 'controllers/Users/profile');
        $this->Acl->allow($Role, 'controllers/Users/logout');
    }
    
/**
 * Email user credentials
 *
 */
    function _sendCredentials($data) {
        $username = $data['username'];
        $name = ($data['name']) ? $data['name'] : $data['username'];
        $password = $data['password'];
        $this->set(compact('username', 'password', 'name'));
        /* set up registration email */
        $this->Email->to = $data['email'];
        $this->Email->bcc = array('registrations@april-app.nl');
        $this->Email->subject = __('Your registration at the UvA Internship website', true);
        $this->Email->replyTo = 'April Registrations <registrations@april-app.nl>';
        $this->Email->from = 'April Registrations <registrations@april-app.nl>';
        $this->Email->template = 'new_user_credentials';
        $this->Email->sendAs = 'both';
        $this->Email->send();
    }
}
?>