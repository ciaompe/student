<?php 

class MemberModel
{
	private $_db;
	
	public function __construct()
	{
		$this->_db = Database::getInstance();
	}

	public function changeEmail($email, $usergroup, $uid) {

		$this->_db->update('users', $uid, array('email' => $email), 'user_id');

		if ($usergroup == 3) {
			$this->_db->update('student', $uid, array('studentEmail' => $email), 'user_id');
		}
		if ($usergroup == 2) {
			$this->_db->update('lecture', $uid, array('lectureEmail' => $email), 'user_id');
		}
		if ($usergroup == 1) {
			$this->_db->update('coordinator', $uid, array('co_email' => $email), 'user_id');
		}
	}

	public function validPassword($password, $uid) {

		$password = $this->_db->mycustomQueryWhere('SELECT * FROM users', array('password', '=', md5($password), 'user_id', '=', $uid) );

		if ($password->count()) {
			return true;
		}
		return false;
	}

	public function updatePassword($password, $uid) {

		$this->_db->update('users', $uid, array('password' => md5($password)), 'user_id');

	}

}


 ?>