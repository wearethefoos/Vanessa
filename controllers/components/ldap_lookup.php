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

App::import('Component', 'Session');

class LdapLookupComponent extends Object {
   var $controller;
   var $host         = 'ldap.uva.nl';
   var $baseDn       = 'o=Universiteit van Amsterdam, c=NL';
   var $studentDn    = 'ou=Studenten, ';
   var $medewerkerDn = 'ou=Medewerkers, ';

   var $ds;

   function find($uvanetid, $password, $lookup)
   {
	  $this->controller->Session->delete('Message'); //unset flash messages..
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
		 $this->controller->Session->setFlash(__('Could not connect to LDAP directory! It might be down.. Please try again.', true));
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
			$this->controller->Session->setFlash(__('Could not connect to LDAP directory! Is your password correct?', true), 'flash/modal', array('class' => 'error'));
            return false;
         } else {
            $this->log('Supervisor assumed', 'debug');
         }
      } else {
         $this->log('Student assumed', 'debug');
      }

      $filter = '(uid='.$lookup.')';
	  $this->log('Starting query for ' . $lookup, 'debug');
      //$attributes = array('cn', 'ou', 'mail');
      $attributes = array();
      $attrsonly = false;
      $sizelimit = 1;
	  $dn = $this->studentDn.$this->baseDn; // TODO: we will only lookup students for now..
	  $role_id = 11; // student role_id
      $lookup_user = ldap_search($this->ds, $dn, $filter, $attributes, $attrsonly, $sizelimit);
      // retrieve information
      $entry = ldap_get_entries($this->ds, $lookup_user);
	
      if(!ldap_unbind($this->ds)) {
          $this->log('Could not unbind to LDAP server', 'error');
          $this->log('Could not unbind to LDAP server', 'debug');
          return false;
      } else {
          $this->log('Unbound LDAP server', 'debug');
      }

      $ldap_user = isset($entry[0]) ? $entry[0] : '';
      
      if (isset($ldap_user['cn'][0])) {                                    // retrieve supervisor name
         $this->log('Found in LDAP', 'debug');
         $username = $ldap_user['cn'][0];
      } else {
		 $this->log('Not found in LDAP', 'debug');
         return -1;              // -1: This means user is not found in LDAP
	  }

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

	/* standard component init stuff below */
	//called before Controller::beforeFilter()
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
	}

	//called after Controller::beforeFilter()
	function startup(&$controller) {
	}

	// //called after Controller::beforeRender()
	// function beforeRender(&$controller) {
	// }
	// 
	// //called after Controller::render()
	// function shutdown(&$controller) {
	// }
	// 
	// //called before Controller::redirect()
	// function beforeRedirect(&$controller, $url, $status=null, $exit=true) {
	// }
}

?>
