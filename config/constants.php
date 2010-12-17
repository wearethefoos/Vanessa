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

Constant::write('ADMIN', 8);
Constant::write('ADMINISTRATOR', 8);
Constant::write('SUPERVISOR', 9);
Constant::write('TEACHER', 10);
Constant::write('STUDENT', 11);

