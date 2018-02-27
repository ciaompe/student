<?php 

class Input {

	public static function exists($type = 'post') {

		switch ($type) {

			case 'post':
				if (!empty($_POST)) {
					return true;
				} else {
					return false;
					
				}
				break;
			case 'get':
				if (!empty($_GET)) {
					return true;
				} else {
					return false;
					
				}
				break;
			default:
				return false;
				
				break;
		}

	}

	public static function get($item) {

		if (isset($_POST[$item])) {
			return $_POST[$item];
		} else if(isset($_GET[$item])) {
			return $_GET[$item];
		}

		return '';

	}

}






 ?>