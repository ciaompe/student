<?php 

class LectureModel {


	private $_db;

	public function __construct() {
		$this->_db = Database::getInstance();
	}

	public function saveLacture($fields = array()) {

		if (!$this->_db->insert('lecturer', $fields)) {
			throw new Exception("There was a problem creating an account.");
		}
	}

	public function getlastinsertlectureid() {
		return $this->_db->lastInsertid();
	}

	public function getalllectures() {
		return $this->_db->getAll("SELECT * FROM lecturer")->results();
	}

	public function validLectureId($lid) {
		$data = $this->_db->get('lecturer', array('lecturerId', '=', $lid));

		if ($data->count()) {
			return $data->results();
		}

		return false;
	}

	public function getLectureSubjects($lid) {
		return $this->_db->mycustomQuery('SELECT subject.sub_id FROM lecturer INNER JOIN lecturer_subject ON lecturer_subject.lect_id = lecturer.lecturerId LEFT JOIN subject on subject.sub_id = lecturer_subject.sub_id', array('lecturerId', '=', $lid))->results();
	}

	public function deletealllecturesubjects($lid) {

		$this->_db->delete('lecturer_subject', array(
			'lect_id', '=', $lid
		));
	}

	public function validEmail($email) {
		$data = $this->_db->get('users', array('email', '=', $email));

		if ($data->count()) {
			return true;
		}

		return false;
	}

	public function updateLecture($fields = array(), $lid, $uid, $email) {
		$this->_db->update('lecturer', $lid, $fields, 'lecturerId');
		$this->_db->update('users', $uid, array('email' => $email), 'user_id');
	}

	public function checkPermission($lid, $subid) {

		$data = $this->_db->get('lecturer_subject', array('lect_id', '=', $lid));

		if($data->count()) {
			
			foreach ($data->results() as $value) {
				if ($value->sub_id == $subid) {
					return true;
				}
			}
		}
		return false;
	}

	public function getLectureid($user_id) {
		return $this->_db->get('lecturer', array('user_id', '=', $user_id))->first();
	}

	public function getLecturerid($lid) {
		return $this->_db->get('lecturer', array('lecturerId', '=', $lid))->first();
	}
	public function deleteLecurer($lid) {
		$this->_db->delete('users', array('user_id', '=', $this->getLecturerid($lid)->user_id));
		$this->_db->delete('lecturer', array('lecturerId', '=', $lid));

	}
	public function banLecturer($lid) {
		$this->_db->update('users', $this->getLecturerid($lid)->user_id, array('user_group' => 4), 'user_id');
	}

	public function unbanLecturer($lid) {
		$this->_db->update('users', $this->getLecturerid($lid)->user_id, array('user_group' => 2), 'user_id');
	}
}


 ?>