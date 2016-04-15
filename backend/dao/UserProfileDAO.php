<?php
    
    class UserProfileDAO extends GenericDAO {

        function __construct($conexion, $logObject = null) {
            parent::__construct($conexion, $logObject);
        }

        private function completar(Array $data){
            $profile = new Profile();
            $profile->setProfileId($data['profile_id']);
            $profile->setProfileDesc($data['profile_desc']);
            return $profile;
        }


        public function insert($userId, $profileId) {
        $returnValue = 'ko';
        $aParams = array($userId, $profileId);
        try {
            $sql = "INSERT INTO user_profile (user_id,profile_id) VALUES (?,?)";
            $rs = $this->db->Execute($sql, $aParams);
            $rs->Close();
            $returnValue = 'ok';
        } catch (ADODB_Exception $adodb_exception) {
            //$logInfo['exception'] = $adodb_exception->getMessage();
            $message = "'[" . __CLASS__ . "] Error when executing method ' insert '";
            // Devuelve false si no puede hacer el rollback
            if (!$this->rollbackTransaction()) {
                //if(!is_null($this->log)) $this->log->log("ERROR en la ejecucion de base de datos: ".print_r($logInfo, true), PEAR_LOG_ERR);
                $message .= " Error al hacer el ROLLBACK.";
                // Si no pudo hacer el Rollback, tiro una excepcion de transaccion, y agrego la info de la excepcion original
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            //if(!is_null($this->log)) $this->log->log("ERROR en la ejecucion de base de datos: ".print_r($logInfo, true), PEAR_LOG_ERR);
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $returnValue;
	}

    }
?>