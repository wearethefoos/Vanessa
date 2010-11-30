<?php
/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 */
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		define('CAKE_CORE_INCLUDE_PATH',  DS . 'Applications' . DS . 'XAMPP' . DS . 'xamppfiles' . DS . 'share' . DS . 'cakephp');
	}


/**
 * Local config settings!
 */
Configure::write('debug', 2);