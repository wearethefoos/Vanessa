<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppModel extends Model {
	var $actsAs = array('Containable');
	
	/**
	 * Get ID of the logged in student from the session, if any.
	 * 
	 * @return mixed array with student data | false
	 */
	public function getStudentIdFromSession() {
		App::import('Core', 'Controller');
		App::import('Core', 'SessionComponent');
		$Controller = new Controller();		
		$Session = new SessionComponent();
		$Session->startup($Controller);
		
		if ($student_id = $Session->read('Auth.User.student_id')) {
			return $student_id;
		} else {
			$this->log('Trying to get studentId failed.. Not a student?');
			return false;
		}
	}
}
?>