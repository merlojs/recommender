<?php

include_once(DAO_INTERFACE . 'IPerformerDAO.php');

class PerformerDAO extends GenericDAO implements IPerformerDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        $performer = new Performer();
        $performer->setPerformerId($data['performer_id']);
        $performer->getMovieSeries()->setMovieSeriesId($data['movie_series_id']);
        $performer->getPerson()->setPersonId($data['person_id']);
        $performer->getPerson()->setPersonFirstname($data['firstname']);
        $performer->getPerson()->setPersonLastname($data['lastname']);
        $performer->setPerformerType($data['performer_type']);
        return $performer;
    }
        
    public function insert(Performer $performer) {
        $returnValue = 'ko';
        //$logInfo = array();
        $aParams = array($performer->getMovieSeries()->getMovieSeriesId(), $performer->getPerson()->getPersonId(), $performer->getPerformerType());
        try {
            $sql = "INSERT INTO performer (movie_series_id, person_id, performer_type ) VALUES (?, ?, ?)";
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
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $returnValue;
    }

    public function getId($id) {
        try {
            $aParams = array();
            //$this->db->debug = true;
            $query = "SELECT t.*, p.lastname, p.firstname  FROM performer t "
                    . "JOIN person p on p.person_id = t.person_id "
                    . " WHERE t.performer_id= $id";
            $rs = $this->db->Execute($query, $aParams);
            $performer = $this->completar($rs->GetRowAssoc(false));

            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscarId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $performer;
    }
    
    public function checkCast($movieSeriesId) {
        $arrayList = array();
        try {
            $aParams = array();
            //$this->db->debug = true;
            $query = "SELECT t.*, p.lastname, p.firstname  FROM performer t "
                    . "JOIN person p on p.person_id = t.person_id "
                    . " WHERE t.movie_series_id= $movieSeriesId";
            $rs = $this->db->Execute($query, $aParams);
            while (!$rs->EOF) {
                $performer = $this->completar($rs->GetRowAssoc(false));
                $arrayList[] = $performer;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' checkCast '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }

    public function search($cantidad, $pagina, $idMovie) {
        $arrayList = array();
        try {
            //$this->db->debug = true;
            $aParams = array();
            $arrayList = array();
            $sql = "SELECT t.*, p.lastname, p.firstname FROM performer t "
                    . " JOIN person p on p.person_id = t.person_id "
                    . " WHERE movie_series_id = ".$idMovie;
            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $performer = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $performer;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscar '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }   

    public function update(Performer $performer) {
        try {
            //$this->db->debug = true;
            $aParams = array(
                'performerType' => $performer->getPerformerType(),
                'performer_id' => $performer->getPerformerId()
            );

            $rs = $this->db->Execute("UPDATE performer SET performer_type = ? WHERE performer_id = ?", $aParams);
            $rs->Close();

        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method  ' update ' ";
            if (!$this->rollbackTransaction()) {
                $message .= " Error al hacer el ROLLBACK.";
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return true;
    }

    public function baja(Performer $performer) {
        
    }

    public function delete(Performer $performer) {
        try {
            $query = "DELETE FROM performer WHERE performer_id=" . $performer->getPerformerId();
            $rs = $this->db->Execute($query);
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method  ' delete '";
            if (!$this->rollbackTransaction()) {
                $message .= " Error al hacer el ROLLBACK.";
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return ($this->db->Affected_Rows() > 0 ? true : false);
    }
    
    public function deleteCascading($movieSeriesId) {
        try {
            $query = "DELETE FROM performer WHERE movie_series_id =" . $movieSeriesId;
            $rs = $this->db->Execute($query);
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method  ' deleteCascading '";
            if (!$this->rollbackTransaction()) {
                $message .= " Error al hacer el ROLLBACK.";
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return ($this->db->Affected_Rows() > 0 ? true : false);
    }
    
    
    public function count() {
        try {
            $aParams = array();
            $sql = "SELECT COUNT(*) cantidad FROM performer t WHERE 1=1 ";
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