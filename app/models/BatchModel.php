<?php 

class BatchModel{

	private $_db;

	public function __construct() {
		$this->_db = Database::getInstance();
	}

	public function getallBatch() {
		//getting all batches from database using getAll method in database class
		return $this->_db->getAll("SELECT * FROM batch ORDER BY batch_id DESC")->results();
	}

	public function addBatch($fields = array()) {

		if (!$this->_db->insert('batch', $fields)) {
			throw new Exception("There was a problem creating an account.");
		}
	}

	public function validBatchID($id) {

		$data = $this->_db->get('batch', array('batch_id', '=', $id));

		if ($data->count()) {
			return $data->results();
		}

		return false;
	}

	public function getstudentBYbatch($batchid) {
		return $this->_db->get('student', array('batchId', '=', $batchid))->results();
	}

	public function deleteBatch($batchid) {
		$students = $this->getstudentBYbatch($batchid);
		foreach ($students as $student) {
			$this->_db->delete('users', array('user_id', '=', $student->user_id));
			$this->_db->delete('student', array('studentId', '=', $student->studentId));
		}
		$this->_db->delete('batch', array('batch_id', '=', $batchid));
	}

	public function validateBatchNumber($batchid) {
		$batch = $this->_db->get('batch', array('batch_number', '=', $batchid));
		if ($batch->count()) {
			return false;
		}
		return true;
	}

	public function updateBatch($bid, $fields) {
		$this->_db->update('batch', $bid, $fields, 'batch_id');
	}
}

?>