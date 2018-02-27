<?php 

class User{

	private $_db,
			$_data,
			$_sessionName,
			$_isLoggedIn,
			$_resetuserId,
			$_sessionID;


	public function __construct($user = null) {

		$this->_db = Database::getInstance();
		$this->_sessionName = Config::get('session/session_name');

		if (!$user) {

			if (Session::exists($this->_sessionName)) {

				$user = Session::get($this->_sessionName);

				if ($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					$this->_isLoggedIn = false;
				}

			}
		} else {
			$this->find($user);
		}

	}

	public function saveUser($fields = array()) {

		if (!$this->_db->insert('users', $fields)) {
			throw new Exception("There was a problem creating an account.");
		}

	}


	public function find($user = null) {
		if ($user) {//check user is not null
			if (is_numeric($user)) {//check user data is numeric
				$field = 'user_id';//set field variable to user_id
			}else if(filter_var($user, FILTER_VALIDATE_EMAIL)) {//check user data is equal to the email address
				$field = 'email';//set field varible to email
			}else {
				$field = 'username';//set field variable to username
			}
			//call get method in database class with parameters tablename, filed and user data
			$data = $this->_db->get('users', array($field, '=', $user));
			//check if get query has count then asign row data to _data variable
			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function login($username, $password) {//this method for check user credentials
		$user = $this->find($username);//this method for finding user using username or email
			if ($user) {//if use is found then check the password
				if ($this->data()->password == md5($password) ) {
					//checking user group is not equal to 4, that means the user is not banned
					if ($this->data()->user_group != 4) {
						//store logged in user id in the session
						Session::put($this->_sessionName, $this->data()->user_id);
						return true;
					}
				}
			}
		return false;
	}

	public function exsits() {

		if (!empty($this->_data)) {
			return true;
		}
		
		return false;
	}

	public function data() {
		return $this->_data;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	public function logout() {

		$this->_db->delete('users_session', array(
			'user_id', '=', $this->data()->user_id
		));

		Session::delete($this->_sessionName);
	}


	public function email($email) {

		return $this->_db->get('users', array('email', '=', $email));
	}

	public function check_email($email) {

		if ($this->email($email)->count()) {
			return true;
		}

		return false;
	}

	public function check_has_email_token($email) {

		$token = $this->_db->get('token', array('user_id', '=', $this->email($email)->first()->user_id));

		if ($token->count()) {
			return true;
		}
		
		return false;

	}

	public function saveToken($email) {

		$token = Hash::unique();

		$this->_db->insert('token', array(

			'user_id' => $this->email($email)->first()->user_id,
			'hash'	  => $token

		));

		$path =  "http://" . $_SERVER['HTTP_HOST'];

		$body = '<p>Somebody recently asked to reset your MYSARA account password, Follow bellow link to reset your password</p><a href="'.$path.'/reset?code='.$token.'">Click here to change your password.</a><br>';

		$args = array(
		    'isHTML'  => true,
		    'debug'   => true,
		    'to'      => $email,
		    'sender'  => 'MYSARA INSITITUTE',
		    'from'    => 'no-reply@mysara.com',
		    'replyTo' => 'contact@mysara.com',
		    'subject' => 'Reset your MYSARA Account Password',
		    'message' => $body,
		    'charset' => 'utf-8',
		    'errorMsg'=> 'Error!',
		    'successMsg' => 'Thank you!'
		);

		$email = new Email($args);
		$email->send();

	}

	public function saveTokenforchangepw($email, $userid) {

		$token = Hash::unique();

		$this->_db->insert('token', array(

			'user_id' => $userid,
			'hash'	  => $token

		));

		$path =  "http://" . $_SERVER['HTTP_HOST'];

		$body = '<p>Please reset your MYSARA account default password, Follow this link to reset your password</p><br><a href="'.$path.'/reset?code='.$token.'">Click here to change your password.</a><br>';

		$args = array(
		    'isHTML'  => true,
		    'debug'   => true,
		    'to'      => $email,
		    'sender'  => 'MYSARA INSITITUTE',
		    'from'    => 'no-reply@mysara.com',
		    'replyTo' => 'contact@mysara.com',
		    'subject' => 'Reset your MYSARA Account Password',
		    'message' => $body,
		    'charset' => 'utf-8',
		    'errorMsg'=> 'Error!',
		    'successMsg' => 'Thank you!'
		);

		$email = new Email($args);
		$email->send();

	}

	public function deleteToken($email) {

		$this->_db->delete('token', array(

			'user_id', '=', $this->email($email)->first()->user_id

		));
	}


	public function forgot_pw_request_code($email) {

		if ($this->check_has_email_token($email)) {

			$this->deleteToken($email);

			$this->saveToken($email);

		} else {

			$this->saveToken($email);
		
		}

	}


	public function valid_reset_code($reset_code) {

		$reset_code_valid = $this->_db->get('token', array('hash', '=', $reset_code));

		if ($reset_code_valid->count()) {

			$this->_resetuserId = $reset_code_valid->first()->user_id;

			return true;
		}


		return false;

	}

	public function reset_password($fields = array(), $reset_code) {

		$reset_code_valid = $this->_db->get('token', array('hash', '=', $reset_code));

		if ($reset_code_valid->count()) {
			$this->_resetuserId = $reset_code_valid->first()->user_id;
		}

		$this->_db->update('users', $this->_resetuserId, $fields, 'user_id'); //$table, $id, $fields, $table_id

		$this->_db->delete('token', array(
			'user_id', '=', $this->_resetuserId
		));
	}



	public function getlastinsertid() {
		return $this->_db->lastInsertid();
	}

	public function checkuid($id) {

		$uid = $this->_db->get('users', array('user_id', '=', $id));

		if ($uid->count()) {
			return true;
		}
		return false;
	}


	//user permissions
	
	//check logged in user is coordinator
	public function coordinator() {
		if ($this->data()->user_group == 1) {
			return true;
		}
		return false;
	}
	//check logged in user is lecturer
	public function lecturer() {
		if ($this->data()->user_group == 2) {
			return true;
		}
		return false;
	}
	//check logged in user is student
	public function student() {
		if ($this->data()->user_group == 3) {
			return true;
		}
		return false;
	}
	//check user is banned
	public function banned() {
		if ($this->data()->user_group == 4) {
			return false;
		}
	}


	public function getUserDetails($user_id) {
		return $this->_db->get('users', array('user_id', '=', $user_id))->first();
	}

}


?>