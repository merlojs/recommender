<?php

/**
 * Description of GenericDAO
 *
 */
include_once(DAO_INTERFACE . 'IGenericDAO.php');

class GenericDAO implements IGenericDAO {

	/**
	 * Database Connection
	 * @var ADOConnection
	 */
	protected $db;
	protected $log;

	public function __construct($conexion, $logObject = null) {
		$this->db = $conexion;

		if (!is_null($logObject)) {
			$this->log = $logObject;
		}

	}

	public function startTransaction() {
		return $this->db->BeginTrans();
	}

	public function endTransaction() {
		return $this->db->CommitTrans();
	}

	public function rollbackTransaction() {
		return $this->db->RollbackTrans();
	}

	public function autoIncrement($sec) {
		$res = $this->db->Execute('SELECT :sec nextval FROM dual commit', array("sec" => $sec));
		return $res->_array[0]['NEXTVAL'];
	}

}

?>
