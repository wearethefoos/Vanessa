<?php
/**
 * Constants used in code.
 */

Class Constant {
	public static function write($key, $value) {
		if (!defined(strtoupper($key))) {
			define($key, $value);
		}
	}
}

Constant::write('DISLIKE', 99);