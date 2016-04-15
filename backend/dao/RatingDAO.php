<?php

include_once(DAO_INTERFACE . 'IRatingDAO.php');

class RatingDAO extends GenericDAO implements IRatingDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        $rating = new Rating();
        $rating->setRatingId($data['rating_id']);
        $rating->getUser()->setUserId($data['user_id']);
        $rating->getMovieSeries()->setMovieSeriesId($data['movie_series_id']);
        $rating->setRatingScore($data['rating_score']);
        
        return $rating;
    }

    public function save($rating) {

        $returnValue = 'ko';
        $this->db->debug = true;
        $aParams = array($rating->getUser()->getUserId(),
            $rating->getMovieSeries()->getMovieSeriesId(),    
            $rating->getRatingScore()
            );
            
        try {
            $sql = $this->db->PrepareSP("INSERT INTO user_rating (user_id, movie_series_id, rating_score) VALUES (?, ?, ?)");
            $rs = $this->db->Execute($sql, $aParams);
            $rs->Close();
            $returnValue = 'ok';
        } catch (ADODB_Exception $adodb_exception) {
            $logInfo['exception'] = $adodb_exception->getMessage();
            $message = "'[" . __CLASS__ . "] Error when executing method ' SAVE '";
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
    
    public function getAllRatings($movieSeriesId) {
        $arrayList = array();
        try {
            $aParams = array();
            $query = "SELECT t.*, FROM user_rating t WHERE t.movie_series_id= $movieSeriesId";
            $rs = $this->db->Execute($query, $aParams);

            while (!$rs->EOF) {
                $rating = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $rating;
                $rs->MoveNext();
            }
            
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' getId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        
        return $arrayList;
    }

    public function getTotalPoints($id) {
        try {
            $aParams = array();
            $query = "SELECT SUM(t.rating_score) total_points FROM user_rating t WHERE t.movie_series_id = $id";
            $rs = $this->db->Execute($query, $aParams);            
            $totalPoints = $rs->Fields("total_points");
            
            if(!is_numeric($totalPoints)){
                $totalPoints = 0;
            }
            
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' getTotalPoints '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $totalPoints;
    }

    public function countVotes($movieSeriesId) {
        try {
            $aParams = array();
            $sql = "SELECT COUNT(*) cantidad FROM user_rating t  WHERE t.movie_series_id = $movieSeriesId ";
            $rs = $this->db->Execute($sql, $aParams);
            $cantidad = $rs->Fields("cantidad");
            
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' countVotes '";
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
    
    public function checkPreviousVote($userId, $movieSeriesId) {
        try {
            $aParams = array();
            $sql = "SELECT COUNT(*) cantidad FROM user_rating t WHERE t.user_id = $userId AND t.movie_series_id = $movieSeriesId";
            $rs = $this->db->Execute($sql, $aParams);
            $cantidad = $rs->Fields("cantidad");
            
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error when executing method ' checkPreviousVote '";
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