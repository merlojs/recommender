<?php

include_once(DAO_INTERFACE . 'IPersonDAO.php');

class PersonDAO extends GenericDAO implements IPersonDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        $person = new Person();
        $person->setPersonId($data['person_id']);
        $person->setPersonLastname($data['lastname']);
        $person->setPersonFirstname($data['firstname']);
        $person->getCountry()->setCountryId($data['country_id']);
        $person->setBirthDate($data['birth_date']);
        $person->setPersonImageLink($data['image_link']);
        return $person;
    }

    public function insert(Person $person) {
        $returnValue = 'ko';
        //$this->db->debug = true;
        $aParams = array(
            $person->getPersonLastname(),
            $person->getPersonFirstname(),
            $person->getCountry()->getCountryId(),
            $person->getBirthDate(),
            $person->getPersonImageLink());
        try {
            $sql = "INSERT INTO person ( lastname, firstname, country_id, birth_date, image_link) VALUES ( ?, ?, ?, STR_TO_DATE(?, '%m/%d/%Y'), ?)";
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
        try {
            $aParams = array();
            $query = "SELECT t.*  FROM person t  WHERE t.person_id= $id";
            $rs = $this->db->Execute($query, $aParams);
            $person = $this->completar($rs->GetRowAssoc(false));

            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscarId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $person;
    }

    public function search($cantidad, $pagina) {
        $arrayList = array();
        try {
            $aParams = array();
            $arrayList = array();
            $sql = "SELECT t.*  FROM person t ORDER BY lastname, firstname";
            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $person = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $person;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscar '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }
    
    public function searchByPerformerType($movieSeriesId, $performerType) {        
        $arrayList = array();
        //$this->db->debug = true;
        try {
            $aParams = array();
            $arrayList = array();
            $sql = "SELECT p.* FROM person p INNER JOIN performer pf ON p.person_id = pf.person_id WHERE pf.movie_series_id = ".$movieSeriesId." AND pf.performer_type = '".$performerType."'";
           
            $rs = $this->db->Execute($sql, $aParams);
            
            while (!$rs->EOF) {
                $person = $this->completar($rs->GetRowAssoc(false));
                $arrayList[] = $person;
                $rs->MoveNext();
            }        
            
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscar '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }        
        return $arrayList;
    }

    public function update(Person $person) {
        try {
            //$this->db->debug = true;
            $aParams = array(
                'personLastname' => $person->getPersonLastname(),
                'personFirstName' => $person->getPersonFirstname(),
                'countryId' => $person->getCountry()->getCountryId(),
                'birthDate' => $person->getBirthDate(),
                'imageLink' => $person->getPersonImageLink(),
                'personId' => $person->getPersonId()
            );

            $rs = $this->db->Execute("UPDATE person SET lastname = ?, firstname = ?, country_id = ?, birth_date = STR_TO_DATE(?, '%m/%d/%Y'), image_link = ? WHERE person_id = ?", $aParams);

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

    public function baja(Person $person) {
        
    }

    public function delete(Person $person) {
        try{
            $query = "DELETE FROM person WHERE person_id=". $person->getPersonId();
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
            $sql = "SELECT COUNT(*) cantidad FROM person t  WHERE 1=1 ";
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
    
    public function searchByName($name) {
        try {
            //$this->db->debug = true;
            $aParams = array();
            $arrayList = array();
            $query = "SELECT t.* FROM person t WHERE t.lastname LIKE '%$name%' OR t.firstname LIKE '%$name%' ";
            $rs = $this->db->Execute($query, $aParams);
            
             while (!$rs->EOF) {
                $movieSeries = $this->completar($rs->GetRowAssoc(false));
                $arrayList[] = $movieSeries;
                $rs->MoveNext();
            }
            $rs->Close();
            
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' searchByName '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        var_dump($arrayList);
        return $arrayList;
    }
    
    public function suggestByName($name) {
        try {
            //$this->db->debug = true;
            $aParams = array();
            $arrayList = array();
            $query = "SELECT t.* FROM person t WHERE t.lastname LIKE '%$name%' OR t.firstname LIKE '%$name%' ";
            $rs = $this->db->Execute($query, $aParams);
            
            while (!$rs->EOF) {
                $movieSeries = $this->completar($rs->GetRowAssoc(false));
                $arrayList[] = $movieSeries;
                $rs->MoveNext();
            }
            $rs->Close();
            
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' searchByTitle '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        
        return $arrayList;
    }

}

?>