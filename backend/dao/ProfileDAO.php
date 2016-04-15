<?php
    include_once(DAO_INTERFACE . 'IProfileDAO.php');

    class ProfileDAO extends GenericDAO implements IProfileDAO{

        function __construct($conexion, $logObject = null) {
            parent::__construct($conexion, $logObject);
        }

        private function completar(Array $data){
            $profile = new Profile();
            $profile->setProfileId($data['profile_id']);
            $profile->setProfileDesc($data['profile_desc']);            
            return $profile;
        }


        public function insert(Profile $profile) {
        $returnValue = 'ko';
        //$logInfo = array();
        $aParams = array($profile->getProfileId(), $profile->getProfileDesc());
        try {
            $sql = "INSERT INTO genre (profile_desc) VALUES (?))";
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


        public function getId($id) {
            try{
                $aParams =  array();
                $query = "SELECT t.*  FROM profile t  WHERE t.= $id";
                $rs = $this->db->Execute($query, $aParams);
                if (!$rs->EOF) {
                    $profile = $this->completar($rs->GetRowAssoc(false));
                }
                $rs->Close();
            } catch(ADODB_Exception $adodb_exception){
                $message = "'[".__CLASS__."] Error executing method ' buscarId '";
                throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
            }
            return $profile;
        }
        
        public function getProfileDesc($code) {
            try{
                $profile = null;
                //$this->db->debug = true;
                $aParams =  array($code);
                $query = "SELECT t.* FROM profile t WHERE t.profile_id= ?";
                $rs = $this->db->Execute($query, $aParams);
                if (!$rs->EOF) {
                    $profile = $this->completar($rs->GetRowAssoc(false));
                }
                $rs->Close();
            } catch(ADODB_Exception $adodb_exception){
                $message = "'[".__CLASS__."] Error executing method ' buscarId '";
                throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
            }
            return $profile;
        }


        public function search($cantidad,$pagina){
            $arrayList = array();
            try{
                $aParams = array();
                $arrayList = array();
                $sql = "SELECT t.*  FROM user_profile t ";
                if ($pagina != NULL) {
                    $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
                } else {
                    $rs = $this->db->Execute($sql, $aParams);
                }
                while(!$rs->EOF){
                    $profile = $this->completar($rs->GetRowAssoc(false));
                    
                    $arrayList[] = $profile;
                    $rs->MoveNext();
                }
                $rs->Close();
            } catch(ADODB_Exception $adodb_exception){
                $message = "'[".__CLASS__."] Error executing method ' buscar '";
                throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
            }
            return $arrayList;
        }


        public function update(Profile $profile) {
            try {
            $aParams = array(
                'profileDesc' => $profile->getProfileDesc(),
                'profileId' => $profile->getProfileId(),
            );

            $rs = $this->db->Execute("UPDATE genre SET profile_desc = ?  WHERE  profile_id = ?", $aParams);
            $rs->Close();

        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method  ' update ' ";
            // devuelve false si no puede hacer el rollback
            if (!$this->rollbackTransaction()) {
                $message .= " Error al hacer el ROLLBACK.";
                // si no pudo hacer el Rollback, tiro una excepcion de transaccion, y agrego la info de la excepcion original
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return true;
        }

        public function baja(Profile $profile) {
            /*
            try{
                $afectedRows = null;
                $sp = $this->db->PrepareSP("DECLARE i number; BEGIN UPDATE user_profile SET f_baja = sysdate WHERE ; :i := SQL%rowCount; END;");
                
                $this->db->OutParameter($sp, $afectedRows, 'i');
                $rs = $this->db->Execute($sp);
                if ($afectedRows > 0) {
                    $return = 'true';
                } else {
                    $return = 'false';
                }
                $rs->Close();
            } catch(ADODB_Exception $adodb_exception){
                $message = "'[".__CLASS__."] Error executing method ' dar de Baja ' ";
                // devuelve false si no puede hacer el rollback
                if( !$this->rollbackTransaction() ){
                    $message .= " Error al hacer el ROLLBACK.";
                    // si no pudo hacer el Rollback, tiro una excepcion de transaccion, y agrego la info de la excepcion original
                    throw new DAODatabaseTransactionException ($message, $adodb_exception->getCode ());
                }
                throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
            }
            return $return;
             * 
             */
        }


        public function delete(Profile $profile) {
           try{
            $query = "DELETE FROM genre WHERE genre_id=". $profile->getProfileId();
            $rs = $this->db->Execute($query);
            $rs->Close();
        }
        catch(ADODB_Exception $adodb_exception){
            $message = "'[".__CLASS__."] Error when executing method  ' delete '";
            // devuelve false si no puede hacer el rollback
            if( !$this->rollbackTransaction() ){
                $message .= " Error al hacer el ROLLBACK.";
                // si no pudo hacer el Rollback, tiro una excepcion de transaccion, y agrego la info de la excepcion original
                throw new DAODatabaseTransactionException ($message, $adodb_exception->getCode ());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return ($this->db->Affected_Rows() > 0 ? true : false);
        }


        public function count() {
            try {
                $aParams = array();
                $sql = "SELECT COUNT(*) cantidad FROM user_profile t  WHERE 1=1 ";
                $rs = $this->db->Execute($sql, $aParams);
                $cantidad = $rs->Fields("cantidad");
                $rs->Close();
            } catch (ADODB_Exception $adodb_exception) {
                $message = "'[" . __CLASS__ . "] Error executing method ' buscarCantidad '";
                if (isset($rs) && $rs != null) {
                    try {
                        $rs->Close();
                    } catch (Exception $e) {
                        if ($this->log)
                            $this->log->log(__FUNCTION__ . ': ', PEAR_LOG_DEBUG);
                    }
                }
                throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
            }
            return $cantidad;
        }
    }
?>