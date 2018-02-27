<?php 

class SubjectModel {

	private $_db;

	public function __construct() {
		$this->_db = Database::getInstance();
	}

	public function getallSubjects() {
		return $this->_db->getAll("SELECT * FROM subject")->results();
	}

	public function saveLectureSubjects($fields = array()) {

		if (!$this->_db->insert('lecturer_subject', $fields)) {
			throw new Exception("There was a inserting subjects.");	
		}

	}

	public function validsubid($subid) {

		$data = $this->_db->get('subject', array('sub_id', '=', $subid));

		if ($data->count()) {
			return true;
		}
		return false;
	}
}

 ?>