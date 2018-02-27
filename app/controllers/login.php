<?php

class Login extends Controller {

	private $_user;

	public function __construct() {
		$this->_user = $this->model('User');
	}


	public function index() {

		if ($this->_user->isLoggedIn()) {
			  Session::display('error', 'Logged in users havn\'t permission to access this page');
			  Redirect::to('home');
		}

		if (Input::exists()) { //Checking Inputs are exists in the login request
			$validate = new Validate(); //create new validation instance
			$validation = $validate->check($_POST, array( //call validation check method
				'username' => array('required' => true),//username validate rule
				'password' => array('required' => true)//password validate rule
			));
			if ($validation->passed()) {//check if validation is passed
					//call login method in user class with parameters
					$login = $this->_user->login(escape(Input::get('username')), Input::get('password'));
					//if login method retuned true then redirected to the home page with success message
					if ($login) {
						Session::display('sucess', 'Hi, Welcome to the mysara student management system');
						echo '<script type="text/javascript">window.location = "home"</script>';
						exit();
					} else{
						//if login method returned false then display error message and exit the execution
						echo "<p>Sign in Erorr</p>";
						exit();
					}
			} else {//if validaiton has errors, then loop through all errors to the user
				$errors = $validation->errors();
				foreach ($errors as $error) {
					echo "<p>".$error."</p>";
					exit();
				}
			}
		}
		$this->view('login');
	}
}

?>
