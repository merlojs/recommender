<?php

include_once(DAO_INTERFACE . 'IUserMessageDAO.php');

class UserMessageDAO extends GenericDAO implements IUserMessageDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        $userMessage = new UserMessage();
        $userMessage->setMessageId($data['message_id']);
        $userMessage->setMessageDate($data['message_date']);
        $userMessage->getSender()->setUserId($data['sender_id']);  // user session variable!!!
        $userMessage->getRecipient()->setUserId($data['recipient_id']);
        $userMessage->setMessageText($data['msg_text']);
        $userMessage->getMovieSeries()->setMovieSeriesId($data['movie_series_id']);
        $userMessage->getMovieSeries()->setOriginalTitle($data['original_title']);
        $userMessage->setReadFlag($data['read_flag']);
        return $userMessage;
    }

    public function insert(UserMessage $userMessage) {
        $returnValue = 'ko';
        //$this->db->debug = true;
        $aParams = array(
            $userMessage->getSender()->getUserId(),
            $userMessage->getRecipient()->getUserId(),
            $userMessage->getMessageText(),
            $userMessage->getMovieSeries()->getMovieSeriesId());
            //$userMessage->getReadFlag();  INITIAL STATE IS 0
        try {
            $sql = "INSERT INTO user_message (sender_id, recipient_id, msg_text, movie_series_id, message_date, read_flag) VALUES (?, ?, ?, ?, NOW(), 0)";
            $rs = $this->db->Execute($sql, $aParams);
            $rs->Close();
            $returnValue = 'ok';
        } catch (ADODB_Exception $adodb_exception) {
            //$logInfo['exception'] = $adodb_exception->getMessage();
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
            //$this->db->debug = true;
            $query = "SELECT t.*, m.original_title FROM user_message t JOIN movie_series m on t.movie_series_id = m.movie_series_id WHERE t.message_id=?";
            $rs = $this->db->Execute($query, $aParams);
            $userMessage = $this->completar($rs->GetRowAssoc(false));

            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscarId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $userMessage;
    }

    public function search($cantidad, $pagina, $recipient = null) {
        $arrayList = array();
        try {
            $aParams = array();
            $arrayList = array();
            $sql = "SELECT t.*, m.original_title FROM user_message t JOIN movie_series m on t.movie_series_id = m.movie_series_id WHERE 1=1 ";
            if($recipient != null){
                $sql .= " AND t.recipient_id = ?";
                $aParams[] = $recipient;
            }
            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $userMessage = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $userMessage;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscar '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }
    
    public function previewSearch($cantidad, $pagina, $recipientId = null) {
        $arrayList = array();
        try {
            $aParams = array();
            $arrayList = array();
            $unreadMessages;
            $totalMessages;
            
            $sql = "SELECT t.*, m.original_title FROM user_message t JOIN movie_series m on t.movie_series_id = m.movie_series_id WHERE 1=1 ";
            if($recipient != null){
                $sql .= " AND t.recipient_id = ?";
                $aParams[] = $recipient;
            }
            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $userMessage = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $userMessage;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' buscar '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }

    public function update(UserMessage $userMessage) {
        try {
            $aParams = array(
                'messageDate' => $userMessage->getMessageDate(),
                'senderId' => $userMessage->getSender()->getUserId(),
                'recipientId' => $userMessage->getRecipient()->getUserId(),
                'msgText' => $userMessage->getMessageText(),
                'movieSeriesId' => $userMessage->getMovieSeries()->getMovieSeriesId(),
                'messageId' => $userMessage->getMessageId(),
                'readFlag'  => $userMessage->getReadFlag(),
            );

            $rs = $this->db->Execute("UPDATE user_message SET recipient_id = ?, message_text = ?, movie_series_id = ?, read_flag = ? WHERE  message_id = ?", $aParams);
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
    
    public function markAsRead(UserMessage $userMessage) {
        try {
            $aParams = array(
                'readFlag'  => 1,
                'messageId' => $userMessage->getMessageId()
            );
            $rs = $this->db->Execute("UPDATE user_message SET read_flag = ? WHERE  message_id = ?", $aParams);
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method  ' markAsRead ' ";
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

    public function delete(UserMessage $userMessage) {
        try {
            $query = "DELETE FROM user_message WHERE message_id =" . $userMessage->getMessageId();
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

    public function count($recipient = null) {
        try {
            $aParams = array();
            $sql = "SELECT COUNT(*) cantidad FROM user_message t  WHERE 1=1 ";
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
    
    public function countUnreadMessages($recipientId) {        
        try {
            $aParams = array();
            $sql = "SELECT COUNT(*) cantidad FROM user_message t WHERE t.recipient_id = $recipientId AND t.read_flag = 0";
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