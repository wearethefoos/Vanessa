<?php
/**
 * Short description for file.
 *
 * This file is a custom Auth component. All Authentication goes through
 * here.
 *
 * PHP versions 4 and 5
 *
 * TOP : Technical Support Group of the Faculty of Behavioral Sciences at 
 * 		 the University of Amsterdam (http://www.top.fmg.uva.nl)
 * Copyright 2009-2010, Faculty of Behavioral Sciences, University of 
 *       Amsterdam (http://www.fmg.uva.nl)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     University of Amsterdam (http://www.top.fmg.uva.nl)
 * @link          http://www.top.fmg.uva.nl (Technical Support Group of the Faculty of 
 * 			      Behavioral Sciences at the University of Amsterdam
 * @package       cake
 * @subpackage    cake.vanessa
 * @since         APRIL v.0.0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 *
 * The class inherits all but a few methods from the CakePHP Auth
 * class. Especially the identify method is different and uses the
 * this classes privat _ldapIdentify method.
 * 
 * This makes sure all students and employees who are in the ldap
 * can log into April without having to sign up for a user account.
 * 
 * It is possible to create user accounts that are not in the ldap
 * in APRIL's own database. APRIL looks in her own database first,
 * then checks the ldap if the user does not exist, and copies the
 * ldap user to her own if ldap authentication succeeded.
 * 
 * Every failed login is logged in the security_log table of APRIL's
 * database. If the the 'denied-count' exceeds 5, the user's IP is
 * blocked from logging in. Once every two minutes or so, these logs
 * are cleaned, and the user can try again.
 *
 * @package       cake
 * @subpackage    cake.vanessa
 * @link		  https://knack.fmg.uva.nl/trac/april/browser/trunk/va/controllers/components/ldap_auth.php
 * @author		  B.Boutin@uva.nl, W.R.deVos@uva.nl
 */

App::import('Component', 'Auth', 'Acl');

class LdapAuthComponent extends AuthComponent {
   var $host         = 'ldap.uva.nl';
   var $baseDn       = 'o=Universiteit van Amsterdam, c=NL';
   var $studentDn    = 'ou=Studenten, ';
   var $medewerkerDn = 'ou=Medewerkers, ';

   var $ds;

   // Disable (temporarily) the hashing of the password.
   // In this way the identify method is called with the true password
   // and we can check it with LDAP.
   function hashPasswords($data) {
      return $data;
   }

   function identify($data) {
      App::import('Model', 'SecurityLog');
      $security_log_model = new SecurityLog();
      $ip_adres = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "n.a.");
      $denied_count = 0;
      if ($ip_adres != 'n.a.') {
         $denied_count = $security_log_model->find('count', array('conditions' => array('ip' => $ip_adres, 'login_status' => 'denied')));
         if ($denied_count >= 5) {
        $this->Session->setFlash(__('Your account was locked, due to excessive failed login attempts. You have to wait two minutes, before you can try again. Sorry!!', true), 'flash/modal', array('class' => 'error'));
            return false;
         }
      }
      $security_log_model->create();
      $security_log_record = 
                  array('log_time' => null,
                        'ip' => $ip_adres
                  );
         
      $uvanetid = $data['User.username'];
      $password = $data['User.password'];
      $security_log_record['username'] = $uvanetid;
      
      $state_UserValidated = false;
      $state_UpdateUser = false;
      $state_AddUser = false;

      // Check whether this uvanetid is already registered in the database
      $usermodel = new User();
      $result = $usermodel->find('first', array('conditions' => array('User.username' => $uvanetid)));
      if (!$result){
         // If the user does not exist in the database, check whether it exists in LDAP.
         $result = $this->_ldapIdentify($uvanetid, $password);
         if ($result  !== false) {
            // This user exists in ldap but not in the database
            // Add it to the database and send him to the 2nd step of the registration
            $result['User']['username'] = $uvanetid;
            $result['User']['password'] = $this->password($password);
            $state_UserValidated = true;
            $state_AddUser = true;
         }
      }
      else {
         // Check the password
         if ($result['User']['password'] == $this->password($password))
            $state_UserValidated = true;
         else {
               // This LDAP user exists in the database, but has a wrong password
               // Check whether the password has not changed in LDAP
               $ldap_data = $this->_ldapIdentify($uvanetid, $password);
               if ($ldap_data !== false) {
                  // This LDAP user is in the database but apparently, his
                  // password has changed. This must be updated in the database.
                  // It's maybe not a good idea to update the user data
                  // with the ldap data: this user is already registered in the database,
                  // only his password has been changed. But the user does
                  // not expect that his data are suddenly updated with ldap data.
                  //$result['User'] = array_merge($result['User'], $ldap_data['User']);
                  //$result['UserInfo'] = array_merge($result['UserInfo'], $ldap_data['UserInfo']);
                  $result['User']['password'] = $this->password($password);
                  $state_UpdateUser = true;
                  $state_UserValidated = true;
               }
         }
      }

      $security_log_record['user_id'] = isset($result['User']['id']) ? $result['User']['id'] : null;

      // If the login/password has been validated, 5 cases can occur:
      //    . The user has an uvanetid, but is not yet registered in the database
      //      (state_AddUser is true)
      //      -> The data from LDAP must be stored in the database, and the
      //         user must go to the 2nd step of the registration procedure
      //    . The user has an uvanetid and is registered in the database, but his password
      //      in the database is not equal to the password in LDAP.
      //      (state UpdateUser is true)
      //      -> Change the data in the database
      //    . The user is registered in the database, he is an LDAP user
      //      -> He can go on to the dashboard.
      if (!$state_UserValidated) {
         $this->Session->setFlash(__('I don\'t know you by either that name or that password!', true), 'flash/modal', array('class' => 'error'));
         $login_status = 'denied';
         $result = false;
      }
      else if ($state_AddUser) {
         // If the $user_data does not contain a user id, the resister2 method will add a new user in DPMS
         $login_status = 'not yet registered';
         $this->Session->write('user_data', $result['User'] );
         $this->Session->write('Auth.redirect', array('controller' => 'users', 'action' => 'register'));
         $this->Session->write('Temp.password', $password); // need unhashed pw to login!
         $result = false;
         // By setting _loggedIn to true, the redirection will work
         $this->_loggedIn = true;
      }
      else if ($state_UpdateUser) {
         // save does not work here... The user model is here not initialized with the Acl
         // component, and misses the Aro object.
         // So we call a special udpatePwd function
         App::import('Model', 'User');
         $user_model = new User();
         $lookup = $user_model->read(null, $result['User']['id']);
         if (isset($result['User']['password'])) {
            $lookup['User']['password'] = $result['User']['password'];
            $user_model->save($lookup, false);
         }
         $this->Session->write('Auth.redirect', array('/'));
         //$this->Session->write('Auth.redirect', array('controller' => 'users', 'action' => 'updatePwd/' . $result['User']['id'] . '/' . $result['User']['password']));
         $login_status = 'granted';
      }
      else {
         $login_status = 'granted';
         $this->Session->write('Auth.redirect', array('/'));
         $this->Session->setFlash(__('Access granted!...', true), 'flash/modal', array('class' => 'success'));
      }

      $security_log_record['login_status'] = $login_status;
      $security_log_model->save(array("SecurityLog" => $security_log_record));
      if ($denied_count > 0 && $state_UserValidated) {
         $security_log_model->deleteAll(array('ip' => $ip_adres, 'login_status' => 'denied'));
      }
      return $result['User'];
   }

   private function _ldapIdentify($uvanetid, $password)
   {
      $result = null;
      $this->log('Starting LDAP identification...', 'debug');
      $this->ds = ldap_connect($this->host);
      if (!$this->ds) {
         $this->log('Could not connect to LDAP server', 'error');
         $this->log('Could not connect to LDAP server', 'debug');
         return false;
      } else {
          $this->log('Connected to LDAP...', 'debug');
      }
      if (!ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3)) {
         $this->log('Could not connect to LDAP server (2)', 'error');
         $this->log('Could not connect to LDAP server (2)', 'debug');
         return false;
      } else {
          $this->log('LDAP protocol set...', 'debug');
      }

      $dn = $this->studentDn.$this->baseDn;
      if(!@ldap_bind($this->ds, 'uid='.$uvanetid.', '.$dn, $password)) {
         $dn = $this->medewerkerDn.$this->baseDn;
         if(!@ldap_bind($this->ds, 'uid='.$uvanetid.', '.$dn, $password)) {
            $this->log('Could not bind to LDAP server', 'error');
            $this->log('Could not bind to LDAP server', 'debug');
            return false;
         } else {
            $role_id = TEACHER;
            $this->log('Teacher assumed', 'debug');
         }
      } else {
         $role_id = STUDENT;
         $this->log('Student assumed', 'debug');
      }

      $filter = '(uid='.$uvanetid.')';
      //$attributes = array('cn', 'ou', 'mail', 'telephoneNumber');
      $attributes = array();
      $attrsonly = false;
      $sizelimit = 1;
      $r_ldap_user = ldap_search($this->ds, $dn, $filter, $attributes, $attrsonly, $sizelimit);
      // retrieve information
      $entry = ldap_get_entries($this->ds, $r_ldap_user);

      if(!ldap_unbind($this->ds)) {
          $this->log('Could not unbind to LDAP server', 'error');
          $this->log('Could not unbind to LDAP server', 'debug');
          return false;
      } else {
          $this->log('Unbound LDAP server', 'debug');
      }

      // insert supervisor information
      if(!$entry['count'])                                                // in the local database
         return false;

      $ldap_user = $entry[0];
      if(isset($ldap_user['cn'][0]))                                    // retrieve supervisor name
         $username = $ldap_user['cn'][0];
      else
         return false;

      $result = array('User' => array('role_id' => $role_id));
      if(isset($ldap_user['mail'][0]))                            // retrieve first emailaddress
         $result['User']['email'] = $ldap_user['mail'][0];
      if(isset($ldap_user['telephonenumber'][0]))                 // retrieve telephonenumber
         $result['User']['phone_number'] = $ldap_user['telephonenumber'][0];
      if(isset($ldap_user['displayname'][0]))                     // retrieve displayname
         $result['User']['name'] = utf8_decode($ldap_user['displayname'][0]);
//      if(isset($ldap_user['sn'][0]))                     
//         $result['User']['last_name'] = $ldap_user['sn'][0];  
      if(isset($ldap_user['uvagender'][0]))
         $result['User']['sex'] = $ldap_user['uvagender'][0] == 'M' ? 1 : 2;                 // Gender M or F
//      $ou_count = $ldap_user['ou']['count'];
//      for ($i=0;$i<$ou_count;$i++)
//         $result['dep' . ($i+1)] = $ldap_user['ou'][$i];
      //TODO:ldap_close generates error. Why???
      //ldap_close($this->ds);
      return $result;
   }
}

?>
