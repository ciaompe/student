<?php 

class Forgot extends Controller {

	private $_user;

	public function __construct() {
		$this->_user = $this->model('User');
	}


	public function index() {

		if ($this->_user->isLoggedIn()) {
		  Session::display('error', 'Logged in users havn\'t permission to access this page');
		  Redirect::to('home');
		}


		if (Input::exists()) {

			$validate = new Validate();
			$validation = $validate->check($_POST, array(

				'email' => array(
					'required' => true,
					'valid_email' => true
				)

			));

			if ($validation->passed()) {

				$email = escape(Input::get('email'));

				if (!$this->_user->check_email($email)) {
					echo "<p>Email address not in Database</p>";
					exit();
				} else {

					$this->_user->forgot_pw_request_code($email);
					Session::display('sucess', 'Password reset link send to your email address!');
					echo '<script type="text/javascript">window.location = "login";</script>';
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

		$this->view('forgot');

	}
}

?>