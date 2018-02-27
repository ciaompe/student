<?php 

class Member extends Controller {

	private $_user,
			$_member;

	public function __construct() {
		$this->_user = $this->model('User');
		$this->_member = $this->model('MemberModel');
	}

	public function index() {

		if (!$this->_user->isLoggedIn()) {
		  	Redirect::to('login');
		}

		$action = Input::get('action');

		switch ($action) {
			case 'settings':
				$this->setting();
				break;
			case 'logout':
				$this->logout();
				break;
			default:
				Redirect::to('home');
				break;
		}

	}

	public function setting() {

		$change = Input::get('change');

		switch ($change) {
			case 'email':
				$this->changeEmail();
				break;
			case 'password':
				$this->changePassword();
				break;
		}

		$this->view('memberSettings');
	}

	public function changeEmail() {

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

			if (Input::exists()) {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'email' => array('required' => true)
				));

				if ($validation->passed()) {

					if(escape(Input::get('email')) != $this->_user->data()->email) {
						
						if ($this->_user->check_email(escape(Input::get('email')))) {
							echo "<p>Email already exists</p>";
							exit();
						} else {

							$this->_member->changeEmail(escape(Input::get('email')), $this->_user->data()->user_group, $this->_user->data()->user_id);
							echo '<p style="background:#229955 !important">Updated Success</p>';
							exit();
						}

					} else {
						echo "<p>Please update your email</p>";
						exit();
					}
					

				} else {

					$errors = $validation->errors();
					foreach ($errors as $error) {
						echo "<p>".$error."</p>";
						exit();
					}
						
				}
			}

		} else {
			include '../app/errors/404.php';
			exit();
		}
	}

	public function changePassword() {

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

			if (Input::exists()) {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(
						'Current-Password' => array('required' => true),
						'New-Password' => array('required' => true, 'min' => 5, 'max' => 10),
						'Confirm-Password' => array('required' => true, 'min' => 5, 'max' => 10, 'matches' => 'New-Password')
				));

				if ($validation->passed()) {

					if (!$this->_member->validPassword(Input::get('Current-Password'), $this->_user->data()->user_id)) {
						echo "<p>Current Password Invalid</p>";
						exit();
					}

					$this->_member->updatePassword(Input::get('Confirm-Password'), $this->_user->data()->user_id);
					echo '<p style="background:#229955 !important">Updated Success</p>';
					exit();

				} else {
					$errors = $validation->errors();
					foreach ($errors as $error) {
						echo "<p>".$error."</p>";
						exit();
					}
				}
			}

		} else {
			include '../app/errors/404.php';
			exit();
		}
	}

	public function logout() {
		$this->_user->logout();
		Redirect::to('home');
	}


}

 ?>