<?php

/**
 * Description of DAOFactory
 *
 */
// Loads DAO exceptions required to run DAOFactory
require_once(ROOT_PATH . '/backend/exceptions/DAOException.php');
require_once(ROOT_PATH . '/backend/exceptions/DAOConfigFileException.php');
require_once(ROOT_PATH . '/backend/exceptions/DAODatabaseException.php');
require_once(ROOT_PATH . '/backend/exceptions/DAODatabaseExecuteException.php');
require_once(ROOT_PATH . '/backend/exceptions/DAODatabaseTransactionException.php');
require_once(ROOT_PATH . '/backend/exceptions/DAOModelException.php');
require_once(ROOT_PATH . '/backend/exceptions/DAORequestedClassException.php');

require_once(ROOT_PATH . '/backend/db/ConnectionPool.php');

/**
 * DAOFactory returns an isntance of the requested DAO Object.
 *
 */
class DAOFactory {

	/**
	 * @static
	 */
	private function __construct() {
		
	}

	/**
	 * Returns an istance of a DAO class, defined in configDAO.ini
	 * 
	 * @static
	 * @param String $type Class Name
	 * @return ObjectDAO Instance of the requested class
	 */
	public static function getDAO($type) {

		if (!isset($_SESSION)) {
			session_start();
		}

		$log = null;
               
                // parse configuration file
                $iniFile = ROOT_PATH . "/backend/config/configDAO.ini";
                $data = parse_ini_file($iniFile, true);
                // save parse in session
                $_SESSION['config']['dao'] = $data;  

		$typeDAO = strtolower($type);

		// check for existing Log entries 
		if (key_exists('log', $_SESSION['config']['dao']['includes'])) {
			// include Log class in DAO configuration
			include_once($_SESSION['config']['dao']['includes']['log']);

			// If exists, load Log configuration
			if (key_exists('configLog', $_SESSION['config']['dao']['includes']) && file_exists(ROOT_PATH . $_SESSION['config']['dao']['includes']['configLog'])) {
				// load Log config
				$logConfig = parse_ini_file(ROOT_PATH . $_SESSION['config']['dao']['includes']['configLog'], true);

				$config = $logConfig['config'];
				$handler = $logConfig['params']['handler'];
				$name = $logConfig['params']['name'];
				$ident = $logConfig['params']['ident'];

				// Load Environment
				$ambiente = $logConfig['params']['ambiente'];
				// Load Log Levels 
				$nivel = constant($logConfig['levels'][$ambiente]);

				// Search Log level mask.
				$mask = Log::MAX($nivel);
				// Load Log Object
				$log = Log::singleton($handler, $name, $ident, $config);
				// Set Logging level. Anything beyond this level will be logged.
				$log->setMask($mask);
			}
		}
		// If DB Class file doesn't exist, throw DAOException
		if (!file_exists(ROOT_PATH . $_SESSION['config']['dao']['includes']['poolDb'])) {
			if (!is_null($log))
				$log->log("DB File path not found:" . ROOT_PATH . $_SESSION['config']['dao']['includes']['poolDb']
						, PEAR_LOG_EMERG);
			$message = "Error in INCLUDE path ' poolDb ' in configuration file";
			throw new DAOConfigFileException('ADODb', $message, 0);
		}

		// Check requested DAO exists in config file
		if (!key_exists($typeDAO, $_SESSION['config']['dao']['DAOClases'])) {
			throw new DAOConfigFileException($typeDAO, 'Requested DAO is not configured.', 0);
		}

		// if file for the class i want to include doesn't exist, throw DAOException
		if (!file_exists(ROOT_PATH . $_SESSION['config']['dao']['includes'][$typeDAO])) {
			if (!is_null($log))
				$log->log("DAO class path not found:" . ROOT_PATH . $_SESSION['config']['dao']['includes'][$typeDAO]
						, PEAR_LOG_ERR);
			$message = "Error in INCLUDE path ' $type ' in configuration file";
			throw new DAOConfigFileException($type, $message, 0);
		}

		// Include Connection Pool class
		include_once(ROOT_PATH . $_SESSION['config']['dao']['includes']['poolDb']);
		// Include GenericDAO class, with basic transaction management
		if (file_exists((ROOT_PATH . '/backend/dao/GenericDAO.php')))
			include_once(ROOT_PATH . '/backend/dao/GenericDAO.php');
		// Includ file for my requested class ( $type )
		include_once(ROOT_PATH . $_SESSION['config']['dao']['includes'][$typeDAO]);

		try {
			//Load Logger (if set)
			if (!is_null($log))
				ADODb::$logObject = $log;

			$daoDB = $_SESSION['config']['dao']['DAOConnection'][$typeDAO];
			$iniDBFile = ROOT_PATH . $_SESSION['config']['dao']['includes']['configDb'];
			// Create DB connection, with .ini configuration in sesssion
                        
			$conn = ConnectionPool::getConnection($iniDBFile, $daoDB);
			// Log as info, data related to the creation of DB connection.
			if (!is_null($log))
				$log->log("Getting DB connection.", PEAR_LOG_INFO);
		} catch (ADODbMySQLConnectException $e_mysql_connect) {
			throw new DAODatabaseException($e_mysql_connect->getMessage(), $e_mysql_connect->getCode());
		} catch (ADODbOracleConnectException $e_oracle_connect) {
			throw new DAODatabaseException($e_oracle_connect->getMessage(), $e_oracle_connect->getCode());
		} catch (ADODbNewConnectionException $e_new_connection) {
			throw new DAODatabaseException($e_new_connection->getMessage(), $e_new_connection->getCode());
		} catch (ADODbException $e) {
			$msg = 'Unknown Exception: ' . $e->getMessage();
			throw new DAODatabaseException($msg, $e->getCode());
		}

		// Create an instance of requested type and pass connection to DB
		$daoClass = $_SESSION['config']['dao']['DAOClases'][$typeDAO];
		if (!class_exists($daoClass)) {
			if (!is_null($log))
				$log->log("Class $daoClass not found. Error in configDAO.ini file", PEAR_LOG_ERR);
			// If class doesn't exit, throw DAORequestedClassException
			throw new DAORequestedClassException($daoClass, "Class requested ($daoClass) is not configured in configDAO ", 0);
		}
		return new $daoClass($conn, $log);
	}

}

?>
