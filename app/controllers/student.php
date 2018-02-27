<?php 

class Student extends Controller {

	private $_user,
			$_student,
			$_batch,
			$_subjects,
			$_lecture;

	public function __construct() {
		$this->_user = $this->model('User');
		$this->_student = $this->model('StudentModel');
		$this->_batch = $this->model('BatchModel');
		$this->_subjects = $this->model('SubjectModel');
		$this->_lecture = $this->model('LectureModel');
	}

	public function index() {

		if (!$this->_user->isLoggedIn()) {
		  	Redirect::to('login');
		}

		$action = Input::get('action');

		switch ($action) {

			case 'add':
				$this->addStudent();
				break;
			case 'edit':
				$this->editStudent();
				break;
			case 'update':
				$this->updateStudent();
				break;
			case 'find':
				$this->findStudent();
				break;
			case 'show':
				$this->showStudent();
			break;
			case 'liveEdit':
				$this->liveEditStudent();
			break;
			case 'grade':
				$this->gradeStudent();
				break;
			case 'loadGrading':
				$this->loadGrading();
				break;
			case 'submitGrading':
				$this->submitGrading();
				break;
			case 'updateGrading':
				$this->updateGrading();
				break;
			case 'result':
				$this->getResult();
				break;
			case 'editMe':
				$this->editMe();
				break;
			case 'updateMe':
				$this->updateMe();
				break;
			case 'delete':
				$this->delete();
				break;
			default:
				Redirect::to('home');
				break;
		}

		
	}


	public function addStudent() {

		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}

		$data = array(
			'batch' => $this->_batch->getallBatch(),
			'regid' => $this->_student->getlaststudentregNUm()
		);

		if (Input::exists()) {

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'name' => array('required' => true),
				'studentNic' => array('required' => true, 'valid_nic' => true, 'unique' => 'student'),
				'address' => array('required' => true),
				'dob' => array('required' => true),
				'batch' => array('required' => true),
				'email' => array('required' => true, 'valid_email' => true, 'unique' => 'users'),
				'telephone' => array('required' => true, 'min' => 10, 'max' => 10),
				'mobile' => array('required' => true, 'min' => 10, 'max' => 10),
				'username' => array('required' => true, 'unique' => 'users', 'min' => 2, 'max' => 20),
				'password' => array('required' => true, 'min' => 5, 'max' => 10)
			));
			//if student validation is passed
			if ($validation->passed()) {
				//save user deatils into the user table
				//username, password, email, user_group = 3 (student)
				$this->_user->saveUser(array(
					'username' => escape(Input::get('username')),
					'password' => md5(Input::get('username')),
					'email' => escape(Input::get('email')),
					'user_group' => 3
				));
				//get last insert id from user table
				$lastinsert_u_id = $this->_user->getlastinsertid();
				//save student details into the student table
				$this->_student->saveStudent(array(
					'studentName' => escape(Input::get('name')),
					'studentNic' => escape(Input::get('studentNic')),
					'studentAddress' => escape(Input::get('address')),
					'studentEmail' => escape(Input::get('email')),
					'studentTelhome' => escape(Input::get('telephone')),
					'studentTelmobile' => escape(Input::get('mobile')),
					'studentGender' => Input::get('gender'),
					'studentDob' => escape(Input::get('dob')),
					'studentReg' => date('Y-m-d'),
					'batchId' => escape(Input::get('batch')),
					'user_id' => $lastinsert_u_id
				));
				//send reset password email to the student email address
				$this->_user->saveTokenforchangepw(escape(Input::get('email')), $lastinsert_u_id);
				//redirect to the home page with success message
				Session::display('sucess', 'Student Added Sucessful');
				echo '<script type="text/javascript">window.location = "home"</script>';
				exit();
			}else {
				$errors = $validation->errors();
				foreach ($errors as $error) {
					echo "<p>".$error."</p>";
					exit();
				}
			}

		}

		$this->view('addstudent', $data);
	}

	public function editStudent() {


		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}

		$method = escape(Input::get('method'));

		if ($method == "nic") {
			
			if (Input::exists()) {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'studentNic' => array('required' => true),
				));

				if ($validation->passed()) {

					if(!$this->_student->validNic(escape(Input::get('studentNic')))) {
						echo "<p>NIC is not valid</p>";
						exit();
					} else {

						echo json_encode(array(
							'students' => $this->_student->validNic(escape(Input::get('studentNic'))),
							'batches' => $this->_batch->getallBatch()
						));
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
		} else if($method == "regnum") {

			if (Input::exists()) {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'studentregnum' => array('required' => true),
				));

				if ($validation->passed()) {

					if(!$this->_student->validRegnum(escape(Input::get('studentregnum')))) {
						echo "<p>REG number is not valid</p>";
						exit();
					} else {

						echo json_encode(array(
							'students' => $this->_student->validRegnum(escape(Input::get('studentregnum'))),
							'batches' => $this->_batch->getallBatch()
						));
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

		}

		$this->view('editstudent');
	}

	public function updateStudent() {
		
		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}

		$stuid =  (isset($_GET['stu']) && is_numeric($_GET['stu'])) ? (int)$_GET['stu'] : false;
		$uid =  (isset($_GET['user']) && is_numeric($_GET['user'])) ? (int)$_GET['user'] : false;
		$email = escape(Input::get('myemail'));
		$nic = escape(Input::get('mynic'));

		if (is_numeric($stuid) || $stuid > 0) {

			if ($this->_student->validstudentid($stuid)) {
				
				if (Input::exists()) {

					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'name' => array('required' => true),
						'studentNic' => array('required' => true, 'valid_nic' => true),
						'address' => array('required' => true),
						'dob' => array('required' => true),
						'batch' => array('required' => true),
						'email' => array('required' => true, 'valid_email' => true),
						'telephone' => array('required' => true, 'min' => 10, 'max' => 10),
						'mobile' => array('required' => true, 'min' => 10, 'max' => 10)
					));

					if ($validation->passed()) {

						if (!$this->_user->checkuid($uid)) {
							echo "<p>Student ID is not valid</p>";
							exit();
						}

						if ($email != escape(Input::get('email'))) {
							
							if ($this->_student->validEmail(escape(Input::get('email')))) {
								echo "<p>Email is already exists</p>";
								exit();
							}
						} else if($nic != escape(Input::get('studentNic'))) {

							if ($this->_student->validNic(escape(Input::get('studentNic')))) {
								echo "<p>NIC is already exists</p>";
								exit();
							}
						}

						$this->_student->updateStudent(array(

								'studentName' => escape(Input::get('name')),
								'studentNic' => escape(Input::get('studentNic')),
								'studentAddress' => escape(Input::get('address')),
								'studentEmail' => escape(Input::get('email')),
								'studentTelhome' => escape(Input::get('telephone')),
								'studentTelmobile' => escape(Input::get('mobile')),
								'studentGender' => Input::get('gender'),
								'studentDob' => escape(Input::get('dob')),
								'studentReg' => date('Y-m-d'),
								'batchId' => escape(Input::get('batch'))

						), $stuid , $uid, escape(Input::get('email')));

						Session::display('sucess', 'Student Updated Sucessful');
						echo '<script type="text/javascript">window.location = "home"</script>';
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
	}

	public function findStudent() {

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		} 

		$method = escape(Input::get('method'));

		if($method == "nic") {

			if (Input::exists()) {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'studentNic' => array('required' => true),
				));

				if ($validation->passed()) {

					if(!$this->_student->validNic(escape(Input::get('studentNic')))) {
						echo "<p>NIC is not valid</p>";
						exit();
					} else {

						echo json_encode(array(
							'students' => $this->_student->getstudentDetails(escape(Input::get('studentNic')))
						));
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

		} else if($method == "regnum") {

			if (Input::exists()) {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'studentregnum' => array('required' => true),
				));

				if ($validation->passed()) {

					if(!$this->_student->validRegnum(escape(Input::get('studentregnum')))) {
						echo "<p>REG number is not valid</p>";
						exit();
					} else {

						echo json_encode(array(
							'students' => $this->_student->getstudentDetailsbyregistrationnum(escape(Input::get('studentregnum')))
						));
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

		}
		
		$this->view('findstudent');
	}


	public function showStudent() {

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		} 

		$sid = (isset($_GET['sid']) && is_numeric($_GET['sid'])) ? (int)$_GET['sid'] : false;

		if (!$this->_student->validRegnum($sid)) {
			Session::display('error', 'Student Registration ID not valid');
			Redirect::to('home');
		}

		$data = $this->_student->getstudentDetailsbyregistrationnum($sid);
		$this->view('showstudent', $data);

	}


	public function liveEditStudent() {

		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}

		$sid = (isset($_GET['sid']) && is_numeric($_GET['sid'])) ? (int)$_GET['sid'] : false;

		if (!$this->_student->validRegnum($sid)) {
			Session::display('error', 'Student Registration ID not valid');
			Redirect::to('home');
		}

		$data = array(
			'students' => $this->_student->validRegnum($sid),
			'batches' => $this->_batch->getallBatch()
		);

		$this->view('liveEdit', $data);
	}

	public function gradeStudent() {

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		} 

		$sid = (isset($_GET['sid']) && is_numeric($_GET['sid'])) ? (int)$_GET['sid'] : false;

		if (!$this->_student->validRegnum($sid)) {
			Session::display('error', 'Student Registration ID not valid');
			Redirect::to('home');
		}

		$data = $this->_subjects->getallSubjects();

		$this->view('gradestudent', $data);

	}

	public function loadGrading() {

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		} 

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

			$sid = (isset($_GET['sid']) && is_numeric($_GET['sid'])) ? (int)$_GET['sid'] : false;
			$subid = (isset($_GET['subid']) && is_numeric($_GET['subid'])) ? (int)$_GET['subid'] : false;

			if ($this->_student->validRegnum($sid)) {
				
				if ($this->_subjects->validsubid($sid)) {

					echo json_encode($this->_student->getGrade($sid, $subid));
					exit();
				}
			}

		} else {
			include '../app/errors/404.php';
			exit();
		}

	}


	public function submitGrading() {

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		} 

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

			$sid = (isset($_GET['sid']) && is_numeric($_GET['sid'])) ? (int)$_GET['sid'] : false;
			$subid = (isset($_GET['subid']) && is_numeric($_GET['subid'])) ? (int)$_GET['subid'] : false;

			if ($this->_student->validRegnum($sid)) {
				
				if ($this->_subjects->validsubid($subid)) {

					if (!isset($_POST['grade'])) {
						echo "<p>Please select a grade<p/>";
						exit();
					} else {

						$user_id =  $this->_user->data()->user_id;

						if ($this->_user->coordinator()) {

							$this->_student->saveGrade(array(
								'grade' => escape(Input::get('grade')),
								'sub_id' => $subid,
								'stu_id' => $sid
							));
							echo '<p style="background:#27ae60">Grade Submitted</p>';
							echo "<script>setTimeout(function() {
									     $('.modelcolse').click();
								  }, 1000);</script>";

							//send email to this student
							$this->_student->sendEmail($subid, $sid, 'Submitted');
							exit();
						}
						else {

							if (!$this->_lecture->checkPermission($this->_lecture->getLectureid($user_id)->lecturerId, $subid)) {
								echo "<p>Access Denied<p/>";
								exit();
							} else {

								$this->_student->saveGrade(array(
									'grade' => escape(Input::get('grade')),
									'sub_id' => $subid,
									'stu_id' => $sid
								));
								echo '<p style="background:#27ae60">Grade Submitted</p>';
								echo "<script>setTimeout(function() {
										     $('.modelcolse').click();
									  }, 1000);</script>";

								//send email to this student
								$this->_student->sendEmail($subid, $sid, 'Submitted');
								exit();
							}

						}
					}
				}
			}

		} else {
			include '../app/errors/404.php';
			exit();
		}

	}

	public function updateGrading() {

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		} 

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
			$subid = (isset($_GET['subid']) && is_numeric($_GET['subid'])) ? (int)$_GET['subid'] : false;
			$gid = (isset($_GET['gid']) && is_numeric($_GET['gid'])) ? (int)$_GET['gid'] : false;
			$sid = (isset($_GET['sid']) && is_numeric($_GET['sid'])) ? (int)$_GET['sid'] : false;

			if (!isset($_POST['grade'])) {
				echo "<p>Please select a grade<p/>";
				exit();
			} else {

				if ($this->_subjects->validsubid($subid)) {

					$user_id =  $this->_user->data()->user_id;

					if ($this->_user->coordinator()) {

						$this->_student->updateGrade(array(
							'grade' => escape(Input::get('grade'))
						), $gid);
						echo '<p style="background:#27ae60 !important">Grade Updated</p>';
						echo "<script>setTimeout(function() {
									     $('.modelcolse').click();
								  }, 1000);</script>";
						//send email to this student
						$this->_student->sendEmail($subid, $sid, 'Updated');
						exit();
					}
					else {

						if (!$this->_lecture->checkPermission($this->_lecture->getLectureid($user_id)->lecturerId, $subid)) {
							echo "<p>Access Denied<p/>";
							exit();
						} else {
							$this->_student->updateGrade(array(
							'grade' => escape(Input::get('grade'))
							), $gid);
							echo '<p style="background:#27ae60 !important">Grade Updated</p>';
							echo "<script>setTimeout(function() {
										     $('.modelcolse').click();
									  }, 1000);</script>";
							//send email to this student
							$this->_student->sendEmail($subid, $sid, 'Updated');
							exit();
						}
					}
				}
			}

		}else {
			include '../app/errors/404.php';
			exit();
		}
	}

	public function getResult() {

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		} 

		$sid = (isset($_GET['sid']) && is_numeric($_GET['sid'])) ? (int)$_GET['sid'] : false;

		if (!$this->_student->validRegnum($sid)) {
			Session::display('error', 'Student Registration ID not valid');
			Redirect::to('home');
		}

		$data = array(
			'grade' => $this->_student->getResults($sid),
			'subjects' => $this->_subjects->getallSubjects()
		);

		$this->view('studentResults', $data);


	}

	public function editMe() {

		if(!$this->_user->student()) {
			Session::display('error', 'Your not a student');
			Redirect::to('home');
		}

		$data = $this->_student->getStudentByUserID($this->_user->data()->user_id);
		$this->view('editMe', $data);
	}

	public function updateMe() {

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

			if($this->_user->student()) {

				if (Input::exists()) {

					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'name' => array('required' => true),
						'address' => array('required' => true),
						'dob' => array('required' => true),
						'telephone' => array('required' => true, 'min' => 10, 'max' => 10),
						'mobile' => array('required' => true, 'min' => 10, 'max' => 10)
					));

					if ($validation->passed()) {

						$this->_student->updateMe(array(

							'studentName' => escape(Input::get('name')),
							'studentAddress' => escape(Input::get('address')),
							'studentTelhome' => escape(Input::get('telephone')),
							'studentTelmobile' => escape(Input::get('mobile')),
							'studentGender' => Input::get('gender'),
							'studentDob' => escape(Input::get('dob')),

						), $this->_user->data()->user_id);

						Session::display('sucess', 'Updated Sucessful');
						echo '<script type="text/javascript">window.location = "home";</script>';
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

		} else {
			include '../app/errors/404.php';
			exit();
		}
	}

	public function delete() {
		if(!$this->_user->coordinator()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		}
		$sid =  (isset($_GET['sid']) && is_numeric($_GET['sid'])) ? (int)$_GET['sid'] : false;
		$bid =  (isset($_GET['batchid']) && is_numeric($_GET['batchid'])) ? (int)$_GET['batchid'] : false;

		if (!$this->_student->validRegnum($sid)) {
			Session::display('error', 'Student id is invalid');
			Redirect::to('home');
		}
		$this->_student->deleteStudent($sid);
		Session::display('sucess', 'Student Delete Successful');
		Redirect::to('batch?action=open&id='.$bid);

	}

}

?>