<?php 

class Validate {

	private $_passed = false,
			$_errors = array(),
			$_db = null;

	public function __construct() {
		$this->_db = Database::getInstance();
	}

	public function check($source, $items = array()) {
		
		foreach ($items as $item => $rules) {
			
			foreach ($rules as $rule => $rule_value) {

			
				$value = trim( $source[$item] );
				
				if ($rule == 'required' & empty($value)) {
					$this->addError("{$item} is required");
				}

				else if(!empty($value)) {

					switch ($rule) {

						case 'min':

						if (strlen($value) < $rule_value) {
							$this->addError("{$item} Must be a minium of {$rule_value} characters.");
						}

						break;

						case 'max':

						if (strlen($value) > $rule_value) {
							$this->addError("{$item} Must be a maxxium of {$rule_value} characters.");
						}

						break;

						case 'unique':

						$check = $this->_db->get($rule_value, array($item, '=', $value));

						if ($check->count()) {
							$this->addError("{$item} already exists");
						}

						break;

						case 'matches':


						if ($value != $source[$rule_value]) {
							$this->addError("{$item} is not equal to {$rule_value}.");
						}

						break;


						case 'valid_email':

						if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
							$this->addError("Please enter valid email");
						}

						break;

						case 'valid_nic':

						if (!preg_match("/^[0-9]{9}[vVxX]*$/", $value)) {
							$this->addError("Please enter valid NIC number");
						}

						break;
						
					}
				}

			}
		}

		if (empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	private function addError($error) {
		$this->_errors[] = $error;
	}

	public function passed(){
		return $this->_passed;
	}

	public function errors() {
		return $this->_errors;
	}

}


 ?>