<?php

include_once(DAO_INTERFACE . 'IGenreDAO.php');

class GenreDAO extends GenericDAO implements IGenreDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        $genre = new Genre();
        $genre->setGenreId($data['genre_id']);
        $genre->setGenreDesc($data['genre_desc']);

        return $genre;
    }

    public function insert(Genre $genre) {
        $returnValue = 'ko';
        //$logInfo = array();
        $aParams = array($genre->getGenreId(), $genre->getGenreDesc());
        try {
            $sql = "INSERT INTO genre (genre_desc) VALUES (?))";
            $rs = $this->db->Execute($sql, $aParams);
            $rs->Close();
            $returnValue = 'ok';
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' insert '";
            // Returns false if rollback fails
            if (!$this->rollbackTransaction()) {
                $message .= " Error performing ROLLBACK.";
                // If rollback fails, throw transaction exception, adding info from the original exception
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $returnValue;
    }

    public function getId($id) {
        try {
            $aParams = array();
            $query = "SELECT t.*  FROM genre t  WHERE t.country_id= $id";
            $rs = $this->db->Execute($query, $aParams);
            $genre = $this->completar($rs->GetRowAssoc(false));

            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' getId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return genre;
    }

    public function search($cantidad, $pagina) {
        $arrayList = array();
        try {
            $aParams = array();
            $arrayList = array();
            $sql = "SELECT t.*  FROM genre t ";
            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $genre = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $genre;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' search '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }

    public function update(Genre $genre) {
        try {
            $aParams = array(
                'genreDesc' => $genre->getGenreDesc(),
                'genreId' => $genre->getGenreId(),
            );

            $rs = $this->db->Execute("UPDATE genre SET genre_desc = ? WHERE  genre_id = ?", $aParams);
            $rs->Close();

        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method  ' update ' ";
            // Returns false if rollback fails
            if (!$this->rollbackTransaction()) {
                $message .= " Error performing ROLLBACK.";
                // If rollback fails, throw transaction exception, adding info from the original exception
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return true;
    }

    public function baja(Genre $genre) {
        /*try {
            $afectedRows = null;
            $sp = $this->db->PrepareSP("DECLARE i number; BEGIN UPDATE genre SET f_baja = sysdate WHERE  genre_id = :genreId; :i := SQL%rowCount; END;");
            $this->db->InParameter($sp, $genre->getGenreId(), 'genreId');

            $this->db->OutParameter($sp, $afectedRows, 'i');
            $rs = $this->db->Execute($sp);
            if ($afectedRows > 0) {
                $return = 'true';
            } else {
                $return = 'false';
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' dar de Baja ' ";
            // devuelve false si no puede hacer el rollback
            if (!$this->rollbackTransaction()) {
                $message .= " Error al hacer el ROLLBACK.";
                // si no pudo hacer el Rollback, tiro una excepcion de transaccion, y agrego la info de la excepcion original
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $return;*/
    }

    public function delete(Genre $genre) {
        try{
            $query = "DELETE FROM genre WHERE genre_id=". $genre->getGenreId();
            $rs = $this->db->Execute($query);
            $rs->Close();
        }
        catch(ADODB_Exception $adodb_exception){
            $message = "'[".__CLASS__."] Error when executing method  ' delete '";
            // Returns false if rollback fails
            if( !$this->rollbackTransaction() ){
                $message .= " Error perfoming ROLLBACK.";
                // If rollback fails, throw transaction exception, adding info from the original exception
                throw new DAODatabaseTransactionException ($message, $adodb_exception->getCode ());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return ($this->db->Affected_Rows() > 0 ? true : false);
    }

    public function count() {
        try {
            $aParams = array();
            $sql = "SELECT COUNT(*) genre FROM genre t  WHERE 1=1 ";
            $rs = $this->db->Execute($sql, $aParams);
            $cantidad = $rs->Fields("cantidad");
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' count '";
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