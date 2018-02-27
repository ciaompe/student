<?php

class Hash {
	public static function make($string, $salt = '') {
		return hash('sha256', $string.$salt);
	}

	public static function salt($length) {
		$salt = mcrypt_create_iv($length);
		$salt = base64_encode($salt);
		return $salt;
	}

	public static function unique() {
		return self::make(uniqid());
	}
}

?>