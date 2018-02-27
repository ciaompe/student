<?php 

class Database {


	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0,
			$_insertid = 0;


	//connecting to the database using PDO
	private function __construct() {
		try{
			//create pdo database connection instance
			//hostname, databasename, username, password getting from config array
			$this->_pdo = new PDO('mysql://:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
		
		} catch (PDOException $e){ //catch all pdo connection errors
			print_r($e->getMessage());
			include APP_PATH.'/app/errors/database_error.php'; //display database error.php
			die();
		}
	}


    //public method to create db obj to other pages
	public static function getInstance() {

		if (!isset(static::$_instance)) {
			static::$_instance = new Database();
		}
		return static::$_instance;
	}


	private function dbQuery($sql) {

		$this->_query = $this->_pdo->prepare($sql);

		if ($this->_query->execute()) {

			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
			$this->_count = $this->_query->rowCount();
		}

		return $this;
		
	}

	//public method to create sql query
	private function query($sql, $params = array()){

		$this->_error = false;
		//pdo prepare sql query to private _query variable
		if ($this->_query = $this->_pdo->prepare($sql)) {

			$x = 1;

			foreach ($params as $param) {
				$this->_query->bindValue($x, $param);
				$x++;
			}
			
		}
		//query executing
		if ($this->_query->execute()) {
			//getting all tuples to the result variable
			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
			//getting row counts in the database table
			$this->_count = $this->_query->rowCount();
		} else {

			$this->_error = true;
		}

		return $this;
	}



	private function action($action, $table, $where = array()) {

		//couting $whare has 3 items
		if (count($where) === 3) {
			//create default oparators array
			$oparators = array('=' , '<', '>', '<=', '>=');

			//whare has 3 different type items
			$field   	= $where[0]; //base field name
			$oparator 	= $where[1]; //oparator (ex: =, <, >)
			$value 		= $where[2]; //ex: mpe

			if (in_array($oparator, $oparators)) {
				// setting all dynamic varible to the sql query action 
				$sql = "{$action} FROM {$table} WHERE {$field} {$oparator} ?";
				//giving this sql varible to the query method
				if (!$this->query($sql, array($value))->error()) {
					//if query hasn't errors returning this
					return $this;
				}
			}
		}
		//otherwize return false
		return $this;

	}

	private function customAction($action, $where = array()) {

		//couting $whare has 3 items
		if (count($where) === 3) {
			//create default oparators array
			$oparators = array('=' , '<', '>', '<=', '>=');

			//whare has 3 different type items
			$field   	= $where[0]; //base field name
			$oparator 	= $where[1]; //oparator (ex: =, <, >)
			$value 		= $where[2]; //ex: mpe

			if (in_array($oparator, $oparators)) {
				// setting all dynamic varible to the sql query action 
				$sql = "{$action} WHERE {$field} {$oparator} ?";
				//giving this sql varible to the query method
				if (!$this->query($sql, array($value))->error()) {
					//if query hasn't errors returning this
					return $this;
				}
			}
		}
		//otherwize return false
		return false;

	}

	private function customActionwhereandwhere($action, $where = array()) {
			
		$sql = "{$action} WHERE {$where[0]} {$where[1]} ? AND {$where[3]} {$where[4]} ?";
			
		if (!$this->query($sql, array($where[2], $where[5]))->error()) {
			return $this;
		}
		return false;
	}

	private function customActionlimit($action, $limit) {

		$sql = "{$action} LIMIT ?, ?";

		$this->_pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

		$this->_query = $this->_pdo->prepare($sql);

		if( $this->_query->execute($limit) ) {

			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
			$this->_count = $this->_query->rowCount();

			return $this;

		}
		//otherwize return false
		return false;

	}

	private function customActionlimitwithwhere($action, $limit, $order, $where =array()) {

		if (count($where) === 3) {

			$oparators = array('=' , '<', '>', '<=', '>=');

			$field   	= $where[0];
			$oparator 	= $where[1];
			$value 		= $where[2];

			if (in_array($oparator, $oparators)) {

				$sql = "{$action} WHERE {$field} {$oparator} ? ORDER BY {$order} DESC LIMIT ?, ?";

				$this->_pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

				$this->_query = $this->_pdo->prepare($sql);

				if( $this->_query->execute(array($value, $limit[0], $limit[1])) ) {

					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);

					return $this->_results;
				}

				return false;

			}

		}
		
	}


	private function customActionlimitwithwherenew($action, $limit, $order, $group, $having = null, $where =array()) {

		if (count($where) === 3) {

			$oparators = array('=' , '<', '>', '<=', '>=');

			$field   	= $where[0];
			$oparator 	= $where[1];
			$value 		= $where[2];

			if (in_array($oparator, $oparators)) {

				$sql = "{$action} WHERE {$field} {$oparator} ? GROUP BY {$group} {$having} ORDER BY {$order} DESC LIMIT ?, ?";

				$this->_pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

				$this->_query = $this->_pdo->prepare($sql);

				if( $this->_query->execute(array($value, $limit[0], $limit[1])) ) {

					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);

					return $this->_results;
				}

				return false;

			}

		}
		
	}



	//public methods for accessing database with multiple querys;

	public function mycustomQueryLimitWherenew($action, $limit, $order, $group, $having, $where) {
		return $this->customActionlimitwithwherenew($action, $limit, $order, $group, $having, $where);
	}

	public function mycustomQueryLimitWhere($action, $limit, $order, $where) {
		return $this->customActionlimitwithwhere($action, $limit, $order, $where);
	}

	public function mycustomQueryLimit($action, $limit) {
		return $this->customActionlimit($action, $limit);
	}

	public function mycustomQuery($action, $where) {
		return $this->customAction($action, $where);
	}

	public function mycustomQueryWhere($action, $where) {
		return $this->customActionwhereandwhere($action, $where);
	}

	public function getAll($sql) {
		return $this->dbQuery($sql);
	}

	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}

	public function results() {
		return $this->_results;
	}

	public function first() {
		return $this->results()[0];
	}

	public function insert($table, $fields = array()) {

			$keys = array_keys($fields);
			$values  = '';
			$x = 1;

			foreach ($fields as $field) {
				$values .= '?';
				if ($x < count($fields)) {
					$values .= ', ';
				}
				$x++;
			}
			$sql = "INSERT INTO {$table} (`".implode('`,`', $keys) ."`) VALUES ({$values})";
			if (!$this->query($sql, $fields)->error()) {
				return true;
			}
			
		return false;
	}

	public function update($table, $id, $fields, $table_id) {

			$set = '';
			$x = 1;

			//set
			foreach ($fields as $name => $value) {
				$set .= "{$name} = ?";

				if ($x < count($fields)) {
					$set .= ", ";
				}

				$x++;
			}

			$sql = "UPDATE {$table} SET {$set} WHERE {$table_id} = {$id}";

			if (!$this->query($sql, $fields)->error()) {
				return true;
			}
			
		return false;
	}

	public function error() {

		return $this->_error;
	}

	public function count() {

		return $this->_count;
	}

	public function lastInsertid() {
		$this->_insertid = $this->_pdo->lastInsertId();
		return $this->_insertid;
	}
	

}

 ?>