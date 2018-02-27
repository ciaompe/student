<?php 

class Session {

	public static function exists($name) {
		if (isset($_SESSION[$name])) {
			return true;
		} else {
			return false;
		}
	}

	public static function put($name, $value) {
		return $_SESSION[$name] = $value;
	}

	public static function get($name) {
		return $_SESSION[$name];
	}

	public static function delete($name) {
		if(static::exists($name)) {
			unset($_SESSION[$name]);
		}
	}

	public static function display($name, $string = '') {
		if (static::exists($name)) {
			$session = static::get($name);
			static:: delete($name);
			return $session;
		} else {
			static::put($name, $string);
		}
	}

}

?>