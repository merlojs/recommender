<?php

include_once(DAO_INTERFACE . 'IOriginDAO.php');

class OriginDAO extends GenericDAO implements IOriginDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        $origin = new Origin();
        $origin->setMovieSeries($data['movie_series']);
        $origin->setCountry($data['country']);
        return $origin;
    }

    public function insert($movieSeriesId, $countryId) {
        $returnValue = 'ko';
        $aParams = array($movieSeriesId, $countryId);
        try {
            $sql = "INSERT INTO origin (movie_series_id, country_id) VALUES (?, ?)";
            $rs = $this->db->Execute($sql, $aParams);
            $rs->Close();
            $returnValue = 'ok';
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' insert '";
            if (!$this->rollbackTransaction()) {
                $message .= " Error performing ROLLBACK.";
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $returnValue;
    }

    public function getId($id) {
        try {
            $aParams = array();
            $query = "SELECT t.*  FROM origin t  WHERE t.= $id";
            $rs = $this->db->Execute($query, $aParams);
            $origin = $this->completar($rs->GetRowAssoc(false));

            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscarId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $origin;
    }

    public function search($cantidad, $pagina) {
        $arrayList = array();
        try {
            $aParams = array();
            $arrayList = array();
            $sql = "SELECT t.*  FROM origin t ";
            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $origin = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $origin;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscar '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }

    public function update($movieSeriesId, $countryId) {
        try {
            $aParams = array(
                'countryId' => $genre->getGenreDesc(),
                'movieSeriesId' => $genre->getGenreId(),
            );

            $rs = $this->db->Execute("UPDATE genre SET country_id = ?  WHERE movie_series_id = ?", $aParams);
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

    public function baja(Origin $origin) {
        
    }

    public function delete($movieSeriesId) {
        try{
            //$this->db->debug = true;
            $query = "DELETE FROM origin WHERE movie_series_id=". $movieSeriesId;
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
            $sql = "SELECT COUNT(*) cantidad FROM origin t  WHERE 1=1 ";
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