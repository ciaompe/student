<?php 

class Lecturer extends Controller {

	public $_user,
		   $_lecture,
		   $_subject;

	public function __construct() {
		$this->_user = $this->model('User');
		$this->_lecture = $this->model('LectureModel');
		$this->_subject = $this->model('SubjectModel');
	}

	public function index() {

		if (!$this->_user->isLoggedIn()) {
		  	Redirect::to('login');
		}

		$action = Input::get('action');

		switch ($action) {

			case 'add':
				$this->addLecture();
				break;

			case 'all':
				$this->allLectures();
				break;

			case 'edit':
				$this->editLecture();
				break;

			case 'update':
				$this->updateLecture();
				break;

			case 'delete':
				$this->deleteLecture();
				break;
			case 'ban':
				$this->banLecture();
				break;
			case 'unban':
				$this->unbanLecture();
				break;	

			default:
				Redirect::to('home');
				break;
		}

	}


	public function addLecture() {

		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}

		if (Input::exists()) {

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'lecturerName' => array('required' => true),
				'email' => array('required' => true, 'valid_email' => true, 'unique' => 'users'),
				'username' => array('required' => true, 'unique' => 'users'),
				'password' => array('required' => true)
			));

			$subid = Input::get('subjects'); 

			if ($validation->passed()) {

				if ($subid == "") {
					echo "<p>Please Select a Subject or Subjects</p>";
					exit();
				}

				$this->_user->saveUser(array(
					'username' => escape(Input::get('username')),
					'password' => md5(Input::get('username')),
					'email' => escape(Input::get('email')),
					'user_group' => 2

				));

				$lastinseruid =  $this->_user->getlastinsertid();

				$this->_lecture->saveLacture(array(
					'lecturerName' => escape(Input::get('lecturerName')),
					'lecturerEmail' => escape(Input::get('email')),
					'user_id' => $this->_user->getlastinsertid()
				));

				$lect_id = $this->_lecture->getlastinsertlectureid();

				foreach ($subid as $value) {
					$this->_subject->saveLectureSubjects(array(
						'lect_id' => $lect_id,
						'sub_id' => $value
					));
				}

				//$this->_user->saveTokenforchangepw(escape(Input::get('lecturerEmail')), $lastinseruid);

				Session::display('sucess', 'Lecture Added Sucessful');
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

		$data = $this->_subject->getallSubjects();
		$this->view('addlecture', $data);
	}

	public function allLectures() {

		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}

		$data = $this->_lecture->getalllectures();
		$this->view('alllectures', $data);
	}

	public function editLecture() {

		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}


		$lid = (isset($_GET['lid']) && is_numeric($_GET['lid'])) ? (int)$_GET['lid'] : false;

		if (!$this->_lecture->validLectureId($lid)) {
			Session::display('error', 'Lecture ID is not valid');
			Redirect::to('home');
		}

		 $data = array(
			'lecture' => $this->_lecture->validLectureId($lid),
			'lectureSub' => $this->_lecture->getLectureSubjects($lid),
			'subjects' => $this->_subject->getallSubjects()
		);

		$this->view('editLecture', $data);

	}

	public function updateLecture() {

		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}

		$lid = (isset($_GET['lid']) && is_numeric($_GET['lid'])) ? (int)$_GET['lid'] : false;
		$subid = Input::get('subjects'); 
		$email = Input::get('email');
		$uid = Input::get('uid');

		if (!$this->_lecture->validLectureId($lid)) {
			Session::display('error', 'Lecture ID is not valid');
			echo '<script type="text/javascript">window.location = "home"</script>';
			exit();
		}

		if (Input::exists()) {

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'lectureName' => array('required' => true),
				'lectureEmail' => array('required' => true, 'valid_email' => true)
			));

			if ($validation->passed()) {

				if ($subid == "") {
					echo "<p>Please Select a Subject</p>";
					exit();
				}

				if (!$this->_user->checkuid($uid)) {
					echo "<p>Lecture ID is not valid</p>";
					exit();
				}

				if ($email != escape(Input::get('lectureEmail'))) {

					if($this->_lecture->validEmail(escape(Input::get('lectureEmail')))){
						echo "<p>Email address already exists</p>";
						exit();
					}
				}

				$this->_lecture->deletealllecturesubjects($lid);

				foreach ($subid as $value) {
					$this->_subject->saveLectureSubjects(array(
						'lect_id' => $lid,
						'sub_id' => $value
					));
				}

				$this->_lecture->updateLecture($fields = array(

						'lecturerName' => Input::get('lectureName'),
						'lecturerEmail' => Input::get('lectureEmail')

				), $lid, $uid, Input::get('lectureEmail'));

				Session::display('sucess', 'Lecture details updated successful');
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


	}

	public function deleteLecture() {

		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}
		$lid = (isset($_GET['lid']) && is_numeric($_GET['lid'])) ? (int)$_GET['lid'] : false;

		if (!$this->_lecture->validLectureId($lid)) {
			Session::display('error', 'Lecture ID is not valid');
			Redirect::to('home');
		}

		$this->_lecture->deleteLecurer($lid);
		Session::display('sucess', 'Lecturer Delete Successful');
		Redirect::to('lecturer?action=all');
	}

	public function banLecture() {
		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}
		$lid = (isset($_GET['lid']) && is_numeric($_GET['lid'])) ? (int)$_GET['lid'] : false;

		if (!$this->_lecture->validLectureId($lid)) {
			Session::display('error', 'Lecture ID is not valid');
			Redirect::to('home');
		}

		$this->_lecture->banLecturer($lid);
		Session::display('sucess', 'Lecturer Banned Successful');
		Redirect::to('lecturer?action=all');
	}

	public function unbanLecture() {
		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}
		$lid = (isset($_GET['lid']) && is_numeric($_GET['lid'])) ? (int)$_GET['lid'] : false;

		if (!$this->_lecture->validLectureId($lid)) {
			Session::display('error', 'Lecture ID is not valid');
			Redirect::to('home');
		}

		$this->_lecture->unbanLecturer($lid);
		Session::display('sucess', 'Lecturer Unbanned Successful');
		Redirect::to('lecturer?action=all');
	}

}

 ?>