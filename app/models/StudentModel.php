<?php 

class StudentModel{

	private $_db;

	public function __construct() {
		$this->_db = Database::getInstance();
	}

	public function saveStudent($fields = array()) {
		//database insert method with parameters, table name and data array
		if (!$this->_db->insert('student', $fields)) {
			//if insert query execution is faild then throw new exception to the user
			throw new Exception("There was a problem creating an account.");
		}
	}

	public function getlastinsertstudentid() {
		return $this->_db->lastInsertid();
	}

	public function validstudentid($id) {

		$data = $this->_db->get('student', array('studentId', '=', $id));

		if ($data->count()) {
			return true;
		}

		return false;
	}

	public function validEmail($email) {
		$data = $this->_db->get('users', array('email', '=', $email));

		if ($data->count()) {
			return true;
		}

		return false;
	}

	public function validNic($nic) {

		$data = $this->_db->get('student', array('studentNic', '=', $nic));

		if ($data->count()) {
			return $data->results();
		}

		return false;
	}

	public function validRegnum($regnum) {
		
		$data = $this->_db->get('student', array('studentId', '=', $regnum));

		if ($data->count()) {
			return $data->results();
		}

		return false;
	}

	public function getlaststudentregNUm() {
		return $this->_db->getAll("SELECT studentId FROM student where studentId=(select MAX(studentId)from student)")->results();
	}

	public function updateStudent($fields = array(), $stuid, $uid, $email) {
		//database update with table name, student id, data, filed id
		$this->_db->update('student', $stuid, $fields, 'studentId');
		//database update with table name, user id, data with fields, filed id
		$this->_db->update('users', $uid, array('email' => $email), 'user_id');
	}

	public function updateMe($fields = array(), $userid) {
		$this->_db->update('student', $userid, $fields, 'user_id');
	}


	public function getstudentDetails($nic) {
		return $this->_db->mycustomQuery('SELECT student.studentId, student.studentName, student.studentNic, student.studentAddress, student.studentEmail, student.studentTelhome, student.studentTelmobile, student.studentGender, student.studentDob, student.studentReg, users.username, batch.batch_number FROM student LEFT JOIN batch ON student.batchId = batch.batch_id INNER JOIN users ON student.user_id = users.user_id', array('studentNic', '=', $nic))->results();
	}

	public function getstudentDetailsbyregistrationnum($regnum) {
		return $this->_db->mycustomQuery('SELECT student.studentId, student.studentName, student.studentNic, student.studentAddress, student.studentEmail, student.studentTelhome, student.studentTelmobile, student.studentGender, student.studentDob, student.studentReg, users.username, batch.batch_number FROM student LEFT JOIN batch ON student.batchId = batch.batch_id INNER JOIN users ON student.user_id = users.user_id', array('studentId', '=', $regnum))->results();
	}


	public function getGrade($sid, $sub_id) {

		return $this->_db->mycustomQueryWhere('SELECT grade_id, grade FROM grade', array('stu_id', '=', $sid, 'sub_id', '=', $sub_id))->results();
	}

	public function saveGrade($fields = array()) {

		if (!$this->_db->insert('grade', $fields)) {
			throw new Exception("There was a problem inserting a grade.");
		}

	}

	public function updateGrade($fields = array(), $gid) {
		$this->_db->update('grade', $gid, $fields, 'grade_id');
	}

	public function getResults($sid) {

		return $this->_db->get('grade', array('stu_id', '=', $sid))->results();
	}


	public function sendEmail($subid, $sid, $status) {

		$stu = $this->_db->get('student', array('studentId', '=', $sid))->results();
		$sub = $this->_db->get('subject', array('sub_id', '=', $subid))->results();

		$args = array(
		    'isHTML'  => true,
		    'debug'   => true,
		    'to'      => $stu[0]->studentEmail,
		    'sender'  => 'MYSARA INSITITUTE',
		    'from'    => 'no-reply@mysara.com',
		    'replyTo' => 'contact@mysara.com',
		    'subject' => 'Mysara Result Status',
		    'message' => 'Check your mysara account, <b>'.$sub[0]->sub_name.'</b> result is '.$status. '<br> ',
		    'charset' => 'utf-8',
		    'errorMsg'=> 'Error!',
		    'successMsg' => 'Thank you!'
		);

		$email = new Email($args);
		$email->send();

	}

	public function getStudentByUserID($userid) {
		return $this->_db->get('student', array('user_id', '=', $userid))->first();
	}
	public function getStudentBystudentID($sid) {
		return $this->_db->get('student', array('studentId', '=', $sid))->first();
	}

	public function deleteStudent($sid) {
		//database delete method with table name(users), data with fields
		$this->_db->delete('users', array('user_id', '=', $this->getStudentBystudentID($sid)->user_id));
		//database delete method with table name(student), data with fields
		$this->_db->delete('student', array('studentId', '=', $sid));
	}


}


 ?>