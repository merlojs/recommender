<?php

/* *
 * This class is prepared to read DB connections.
 * Each method receives config file path as a parameter, returning DB connection data.
 *
 */
require_once(ROOT_PATH . '/backend/libraries/adodb/adodb-exceptions.inc.php');
require_once(ROOT_PATH . '/backend/libraries/adodb/adodb.inc.php');

require_once(ROOT_PATH . '/backend/exceptions/ADODbException.php');
require_once(ROOT_PATH . '/backend/exceptions/ADODbMySQLConnectException.php');
require_once(ROOT_PATH . '/backend/exceptions/ADODbMySQLExecuteException.php');
require_once(ROOT_PATH . '/backend/exceptions/ADODbNewConnectionException.php');
require_once(ROOT_PATH . '/backend/exceptions/ADODbOracleConnectException.php');
require_once(ROOT_PATH . '/backend/exceptions/ADODbOracleExecuteException.php');

class ADODb {

    /**
     * @staticvar
     * @var Log Instance of PEAR Log class
     */
    public static $logObject;

    /**
     * @access Private
     * @static
     */ 
    private function  __construct() {}

    /**
     * Creates a Connection Instance to DB
     * @param String $iniFile
     * @param String $dbName
     * @return ADODB
     */
    public static function getConnection($iniFile, $dbName = '') {
        $dbName = strtoupper($dbName);
        $ADODbInstance = null;
        $logInfo = array();
        // Check for existing configuration file
        if (!file_exists($iniFile)) {
            // If Config file does not exist, load default configuration
            $iniFile = ROOT_PATH . '/backend/config/configDB.ini';
        }

        // Retrieve config file data
        $data = parse_ini_file($iniFile, true);        

        if ($dbName == '') {
            $dbName = strtoupper($data['DBConfig']['db_default']);
        }

        if (!isset($data[$dbName]))
             throw new ADODbNewConnectionException("DB configuration does not exist fot the database: $dbName", 0, $logInfo);
  
        $data = $data[$dbName];

        if ($data['db_driver'] == 'oci8') {
            if (!is_null(self::$logObject))
                self::$logObject->log("Searching connection to Oracle DB", PEAR_LOG_INFO);

            $ADODbInstance = self::getOracleConnection($data);
        }
        else if ($data['db_driver'] == 'mysql') {
            $ADODbInstance = self::getMySQLConnection($data);
        }
        return $ADODbInstance;
    }

    /**
     *  Creates connection instance to an Oracle DB Schema    
     *
     * @param string $iniFile ini config file for the connection
     * @return ADODb
     */
    private static function getOracleConnection($data) {
        $db = null;
        $logInfo = array();

        $logInfo['data'] = $data;
        try {
            
            // create connection with oci8 driver.
            $db = ADONewConnection($data['db_driver']);
            
            if (!is_null(self::$logObject))
                self::$logObject->log("Creating Oracle Connection with ADONewConetion", PEAR_LOG_INFO);
            // If an object is not returned, throw exception.
            if (!$db) { // is_object($db)
                $logInfo['line'] = __LINE__;
                $msg = "Error in new Oracle Connection(oci8): ";

                if (!is_null(self::$logObject))
                    self::$logObject->log("Error when creating Oracle DB Connection: " . print_r($data, true), PEAR_LOG_EMERG);

                throw new ADODbNewConnectionException($msg, 0, $logInfo);
            }
        } catch (ADODB_Exception $adodb_exception) {
            $logInfo['line'] = __LINE__;
            $msg = "Error in new Oracle Connection (oci8) : ";

            if (!is_null(self::$logObject))
                self::$logObject->log("Error when creating Oracle DB Connection: " . $adodb_exception->getMessage(), PEAR_LOG_EMERG);

            throw new ADODbNewConnectionException($msg, $adodb_exception->getCode(), $logInfo);
        }

        try {
            // Connect to DB (non-persistent)
            $db->charSet = (!empty($data['db_charset']))?$data['db_charset']:false;
            $db->Connect($data['db_string'], $data['db_usr'], $data['db_pass']);
        } catch (ADODB_Exception $adodb_exception) {
            if (!is_null(self::$logObject))
                self::$logObject->log($adodb_exception->getMessage(), PEAR_LOG_EMERG);
            // Throw ADODbOracleConnectException with resulting message from ADODb_Exception
            throw new ADODbOracleConnectException($adodb_exception->getMessage(), $adodb_exception->getCode(), $logInfo);
        } catch (Exception $e) {
            if (!is_null(self::$logObject))
                self::$logObject->log($E->getMessage(), PEAR_LOG_EMERG);
            // Capture remaining messahes with ADODbException
            throw new ADODbException($e->getMessage(), $e->getCode());
        }

        return $db;
    }

    /**
     *  Creates an connection instance to a MySQL DB schema MySQL.
     *
     * @param string $iniFile ini connection config file
     * @return ADODb
     */
    private static function getMySQLConnection($data) {
        $db = null;
        $logInfo = array();

        $logInfo['data'] = $data;
        try {
            // Create connection with mysql driver 
            $db = ADONewConnection($data['db_driver']);
            // If not an object, throw exception.
            if (!$db) {
                $logInfo['line'] = __LINE__;
                $msg = "Error in new MYSQL Connection: ";
                throw new ADODbNewConnectionException($msg, 0, $logInfo);
            }
        } catch (ADODB_Exception $adodb_exception) {
            $logInfo['line'] = __LINE__;
            $msg = "Error in new MYSQL Connection: ";
            throw new ADODbNewConnectionException($msg, $adodb_exception->getCode(), $logInfo);
        }

        try {
            // Non-persistent connect
            $db->PConnect($data['db_string'], $data['db_usr'], $data['db_pass'], $data['db_name']);
        } catch (ADODB_Exception $adodb_exception) {
            // throw an exception of type ADODbMySQLConnectException with ADODb_Exception message
            throw new ADODbMySQLConnectException($adodb_exception->getMessage(), $adodb_exception->getCode(), $logInfo);
        } catch (Exception $e) {
            // Throw ADODbException with all remaining messages
            throw new ADODbException($e->getMessage(), $e->getCode());
        }

        return $db;
    }

}

?>
