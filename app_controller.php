<?php
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.vanessa
 */
class AppController extends Controller {
    var $components = array('Acl', 'LdapAuth', 'RequestHandler', 'Session', 'DebugKit.Toolbar');
    var $helpers = array('Text', 'Html', 'Form', 'Ajax', 'Javascript', 'Session', 'Date');
    var $keywords;
    var $description;

    function beforeFilter() {
        //$this->persistModel = PRODUCTION_MODE;
        //Configure AuthComponent
        $this->LdapAuth->flashElement = 'flash/modal';
        $this->LdapAuth->actionPath = 'controllers/';
        $this->LdapAuth->authorize = 'actions';
        $this->LdapAuth->userScope = array('User.activated' => 1);
        $this->LdapAuth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->LdapAuth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->LdapAuth->loginRedirect = array('/');
        $this->LdapAuth->allowedActions = array('*');
        
        $this->keywords = __('Vanessa, assignment, student, subject, university of amsterdam, universiteit van amsterdam, uva', true);
        $this->description = __('Welcome to Vanessa, an application for the University of Amsterdam for Automated Assignment,', true)
                            .__('of Students to Subjects at the Psychology division of the Faculty of Behavioral Sciences.', true);
        
        App::import('Core', 'Sanitize');
    }
    
/**
 * Sets ajax layout options if an ajax request.
 * Sets keywords and meta description for layout.
 * 
 * @return void
 */
    function beforeRender() {
        if ($this->RequestHandler->isAjax()) {
            Configure::write('debug', 0);
            $this->Session->delete('Message.flash');
            $this->Session->delete('Message.Auth');
            $this->set('is_ajax', true);
        } else {
            $this->set('is_ajax', false);
        }
        $this->set('keywords', $this->keywords);
        $this->set('description', $this->description);
    }

/**
 * Minifies HTML output.
 * 
 * @return void
 */
//    function afterFilter() {
//        if (Configure::read('debug') == 0)
//            $this->output = str_replace(array("\r", "\r\n", "\n", "\t", "  ", "//<![CDATA[", "//]]>"), "", $this->output);
//    }
}
?>