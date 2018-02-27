<?php 

class Home extends Controller {

	private $_user;

	public function __construct() {
		$this->_user = $this->model('User');
	}

	public function index() {

		if (!$this->_user->isLoggedIn()) {
		  	Redirect::to('login');
		}

		if( $this->_user->coordinator() || $this->_user->lecturer() ){
			$batch = $this->model('BatchModel');
			$data = $batch->getallBatch();
		}

		if ($this->_user->student()) {

			$student = $this->model('StudentModel');
			$subjects = $this->model('SubjectModel');

			$data = array(
			'grade' => $student->getResults($student->getStudentByUserID($this->_user->data()->user_id)->studentId),
			'subjects' => $subjects->getallSubjects()
			);
		}

		$this->view('home', $data);

	}

}



 ?>