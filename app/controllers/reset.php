<?php 

class Reset extends Controller {

	private $_user;

	public function __construct() {
		$this->_user = $this->model('User');
	}

	public function index() {

		if ($this->_user->isLoggedIn()) {
		  Session::display('error', 'Logged in users havn\'t permission to access this page');
		  Redirect::to('home');
		}

		if(!$code = Input::get('code')) {

			Session::display('error', 'Reset code error !, <a href="forgot">Please resend the reset password code</a>');
			Redirect::to('home');

		} else {

				if (!$this->_user->valid_reset_code($code)) {

					Session::display('error', 'Reset code error !, <a href="forgot">Please resend the reset password code</a>');
					Redirect::to('home');

				} else {


					if (Input::exists()) {

							$validate = new Validate();
							$validation = $validate->check($_POST, array(
								'password' => array(
									'required' => true,
									'min' => 6
								),
								're-password' => array(
									'required' => true,
									'matches' => 'password'
								),
							));

							if ($validation->passed()) {

								$this->_user->reset_password(array(

								'password'	=> md5(Input::get('password'))

								), Input::get('code'));

								Session::display('sucess', 'Password reset successfully !');
								echo '<script type="text/javascript">window.location = "login";</script>';
								exit();

							} else {
								$errors = $validation->errors();
								foreach ($errors as $error) {
									echo "<p>".$error."</p>";
									exit();

								}
							}

					}
				}
		}


		$this->view('reset');
	}


}


 ?>