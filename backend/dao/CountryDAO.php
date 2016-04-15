<?php

include_once(DAO_INTERFACE . 'ICountryDAO.php');

class CountryDAO extends GenericDAO implements ICountryDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        $country = new Country();
        $country->setCountryId($data['country_id']);
        $country->setCountryDesc($data['country_desc']);

        return $country;
    }

    public function insert(Country $country) {

        $returnValue = 'ko';
        $aParams = array($country->getCountryId(), $country->getCountryDesc());

        try {
            $sql = $this->db->PrepareSP("BEGIN INSERT INTO country (country_desc) VALUES (?)");
            $rs = $this->db->Execute($sql, $aParams);
            $rs->Close();
            $returnValue = 'ok';
        } catch (ADODB_Exception $adodb_exception) {
            $logInfo['exception'] = $adodb_exception->getMessage();
            $message = "'[" . __CLASS__ . "] Error when executing method ' INSERT '";
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
            $query = "SELECT t.*  FROM country t  WHERE t.country_id= $id";
            $rs = $this->db->Execute($query, $aParams);
            $country = $this->completar($rs->GetRowAssoc(false));

            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' getId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $country;
    }

    public function search($cantidad, $pagina) {
        $arrayList = array();
        try {
            $aParams = array();
            $arrayList = array();
            $sql = "SELECT t.*  FROM country t ";
            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $country = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $country;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' search '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }        

    public function count() {
        try {
            $aParams = array();
            $sql = "SELECT COUNT(*) cantidad FROM country t  WHERE 1=1 ";
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

    public function baja(Country $country) {
        /* in case there is a need to delete country */
    }


}

?>