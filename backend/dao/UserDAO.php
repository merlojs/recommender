<?php

include_once(DAO_INTERFACE . 'IUserDAO.php');

class UserDAO extends GenericDAO implements IUserDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        
        $user = new User();
        $profile = new Profile();
        
        $profile->setProfileId($data['profile_id']);
        $profile->setProfileDesc($data['profile_desc']);
        $profile->setProfileCode($data['profile_desc']);
        
        $user->setUserId($data['user_id']);
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setUserLastname($data['lastname']);
        $user->setUserFirstname($data['firstname']);
        $user->setUserCreationDate($data['creation_date']);
        $user->setUserEnabledFlag($data['enabled_flag']);
        $user->setUserModificationDate($data['modification_date']);        
        $user->setProfile($profile);
        
        return $user;
    }

    public function insert(User $user) {
        $returnValue = 'ko';

        $aParams = array(
            $user->getUsername(),
            $user->getPassword(),
            $user->getUserLastname(),
            $user->getUserFirstname(),
            $user->getUserEnabledFlag(),
            $user->getProfile()->getProfileId()
        );
        try {
            $sql = "INSERT INTO user (username, password, lastname, firstname, creation_date, enabled_flag) VALUES (?, ?, ?, ?, NOW(), ?)";
            $rs = $this->db->Execute($sql, $aParams);
            $rs->Close();            
            $returnValue = $this->db->Insert_ID();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' insert '";
            // Devuelve false si no puede hacer el rollback
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
            $aParams = array(
                "id" => $id
            );
            $query = "SELECT t.*, up.profile_id, p.profile_desc FROM user t JOIN user_profile up ON up.user_id = t.user_id JOIN profile p ON p.profile_id = up.profile_id WHERE t.user_id= $id";

            $rs = $this->db->Execute($query, $aParams);
            while (!$rs->EOF) {
                $user = $this->completar($rs->GetRowAssoc(false));
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscarId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $user;
    }

    public function checkAvailableUser($username) {
        try {
            $user = null;
            $aParams = array();
            $query = "SELECT t.*, up.profile_id, p.profile_desc FROM user t JOIN user_profile up ON up.user_id = t.user_id JOIN profile p ON p.profile_id = up.profile_id WHERE t.username= '" . $username . "'";
            $rs = $this->db->Execute($query, $aParams);
            while (!$rs->EOF) {
                $user = $this->completar($rs->GetRowAssoc(false));
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscarId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $user;
    }

    public function authenticate($username, $password) {
        try {
            $user = null;
            $aParams = array();
            $query = "SELECT t.*, up.profile_id, p.profile_desc FROM user t JOIN user_profile up ON up.user_id = t.user_id JOIN profile p ON p.profile_id = up.profile_id WHERE t.username= '" . $username . "' AND t.password = '" . $password . "'";
            $rs = $this->db->Execute($query, $aParams);
            if (!$rs->EOF) {
                $user = $this->completar($rs->GetRowAssoc(false));
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' authenticate '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        
        return $user;
    }

    public function search($cantidad, $pagina) {
        $arrayList = array();
        try {
            $aParams = array();
            $arrayList = array();
            $sql = "SELECT t.*, up.profile_id, p.profile_desc FROM user t JOIN user_profile up ON up.user_id = t.user_id JOIN profile p ON p.profile_id = up.profile_id";
            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $user = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $user;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscar '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }

    public function update(User $user) {
        try {
            $aParams = array(
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'userLastname' => $user->getUserLastname(),
                'userFirstname' => $user->getUserFirstname(),
                'creationDate' => $user->getUserCreationDate(),
                'enabledFlag' => $user->getUserEnabledFlag(),
                'userId' => $user->getUserId(),
            );

            $rs = $this->db->Execute("UPDATE genre SET username = ?, password = ?, lastname = ?, firstname = ?, creation_date = ?, enabled_flag = ?, modification_date = NOW() WHERE  user_id = ?", $aParams);
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

    public function baja(User $user) {
       
    }

    public function delete(User $user) {
        try {
            $query = "DELETE FROM user WHERE user_id=" . $user->getUserId();
            $rs = $this->db->Execute($query);
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method  ' delete '";
            // devuelve false si no puede hacer el rollback
            if (!$this->rollbackTransaction()) {
                $message .= " Error al hacer el ROLLBACK.";
                // si no pudo hacer el Rollback, tiro una excepcion de transaccion, y agrego la info de la excepcion original
                throw new DAODatabaseTransactionException($message, $adodb_exception->getCode());
            }
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return ($this->db->Affected_Rows() > 0 ? true : false);
    }

    public function count() {
        try {
            $aParams = array();
            $sql = "SELECT COUNT(*) cantidad FROM user t  WHERE 1=1 ";
            $rs = $this->db->Execute($sql, $aParams);
            $cantidad = $rs->Fields("cantidad");
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' count '";
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