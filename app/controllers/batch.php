<?php

class Batch extends Controller {

	public $_user,
			$_batch;

	public function __construct() {
		$this->_user = $this->model('User');
		$this->_batch = $this->model('BatchModel');
	}

	public function index() {

		if (!$this->_user->isLoggedIn()) {
		  	Redirect::to('login');
		}

		$action = Input::get('action');

		switch ($action) {

			case 'add':
				$this->addBatch();
				break;
			case 'open':
				$this->openBatch();
				break;
			case 'all':
				$this->allBatches();
				break;
			case 'edit':
				$this->editBatch();
				break;
			case 'delete':
				$this->delete();
				break;
			default:
				Redirect::to('home');
				break;
		}

	}


	public function addBatch() {

		if (!$this->_user->coordinator()) {
			Session::display('error', 'Your not a Coordinator, Please login as Coordinator');
			Redirect::to('home');
		}

		if (Input::exists()) {

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'batch_number' => array('required' => true, 'unique' => 'batch'),
				'regDate' => array('required' => true)
			));

			if ($validation->passed()) {

				$batch_number =  (is_numeric(Input::get('batch_number'))) ? (int)Input::get('batch_number') : false;

				if (!$batch_number) {
					echo '<p>Batch number must be an Integer</p>';
					exit();
				}

				$this->_batch->addBatch(array(
					'batch_number' =>$batch_number,
					'batch_reg_date' => escape(Input::get('regDate'))
				));

				Session::display('sucess', 'Batch Added Sucessful');
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

		$this->view('addbatch');

	}


	public function openBatch(){

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		} 

		$batchid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : false;

		if (!$this->_batch->validBatchID($batchid)) {
			Session::display('error', 'Batch ID is not valid');
			Redirect::to('home');
		}

		$data = array(
			'batch' => $this->_batch->validBatchID($batchid),
			'stuents' => $this->_batch->getstudentBYbatch($batchid)
		);

		$this->view('openbatch', $data);
	}

	public function allBatches() {

		if (!$this->_user->coordinator() && !$this->_user->lecture()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		}

		$data = $this->_batch->getallBatch();
		$this->view('getallbatches', $data);
	}

	public function delete() {
		if ($this->_user->coordinator()) {
			$batchid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : false;

			if (!$this->_batch->validBatchID($batchid)) {
				Session::display('error', 'Batch ID is not valid');
				Redirect::to('home');
			}
			$this->_batch->deleteBatch($batchid);
			Session::display('sucess', 'Batch Delete Successful');
			Redirect::to('batch?action=all');
		}
	}

	public function editBatch() {
		if (!$this->_user->coordinator()) {
			Session::display('error', 'You havent permission to open this task');
			Redirect::to('home');
		}

		$batchid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : false;

		if ($batch = $this->_batch->validBatchID($batchid)) {

			if (Input::exists()) {
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'batch_number' => array('required' => true),
						'regDate' => array('required' => true)
					));
				if ($validation->passed()) {
					$batch_number =  (is_numeric(Input::get('batch_number'))) ? (int)Input::get('batch_number') : false;

					if (!$batch_number) {
						echo '<p>Batch number must be an Integer</p>';
						exit();
					}

					if($batch_number != $batch[0]->batch_number) {
						if (!$this->_batch->validateBatchNumber($batch_number)) {
							echo '<p>Batch number already exists</p>';
							exit();
						}
					}
					$this->_batch->updateBatch($batchid, array(
						'batch_number' => Input::get('batch_number'),
						'batch_reg_date' => Input::get('regDate')
					));
					Session::display('sucess', 'Batch Updated Sucessful');
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
			$this->view('editbatch', $batch);
		} else {
			Session::display('error', 'Batch ID is not valid');
			Redirect::to('home');
		}


	}

}


 ?>