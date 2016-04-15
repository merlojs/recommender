<?php

include_once(DAO_INTERFACE . 'IMovieSeriesDAO.php');

class MovieSeriesDAO extends GenericDAO implements IMovieSeriesDAO {

    function __construct($conexion, $logObject = null) {
        parent::__construct($conexion, $logObject);
    }

    private function completar(Array $data) {
        $movieSeries = new MovieSeries();
        $genre = new Genre();
        $movieSeries->setMovieSeriesId($data['movie_series_id']);
        $movieSeries->setOriginalTitle($data['original_title']);
        $movieSeries->getGenre()->setGenreId($data['genre_id']);
        $movieSeries->getGenre()->setGenreDesc($data['genre_desc']);
        $movieSeries->getCountry()->setCountryId($data['country_id']);
        $movieSeries->setMovieSeriesFlag($data['movie_series_flag']);        
        $movieSeries->setImdbLink($data['imdb_link']);
        $movieSeries->setYear($data['year']);
        $movieSeries->setMovieImageLink($data['image_link']);
        $movieSeries->setTrailerLink($data['trailer_link']);
        $movieSeries->setSeasons($data['seasons']);        
        
        return $movieSeries;
    }

    public function insert(MovieSeries $movieSeries) {
        $returnValue = 'ko';
        //$this->db->debug = true;
        $aParams = array($movieSeries->getOriginalTitle(),
            $movieSeries->getGenre()->getGenreId(),            
            $movieSeries->getMovieSeriesFlag(),            
            $movieSeries->getImdbLink(),
            $movieSeries->getYear(),
            $movieSeries->getMovieImageLink(),
            $movieSeries->getTrailerLink(),
            $movieSeries->getSeasons());
        try {
            $sql = "INSERT INTO movie_series (original_title, genre_id, movie_series_flag, imdb_link, year, image_link, trailer_link, seasons) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $rs = $this->db->Execute($sql, $aParams);
            $rs->Close();
            $returnValue = $this->db->Insert_ID();
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
            $query = "SELECT t.*, g.genre_desc, c.country_id FROM movie_series t left JOIN genre g ON g.genre_id = t.genre_id left JOIN origin o ON o.movie_series_id = t.movie_series_id left JOIN country c ON o.country_id = c.country_id WHERE t.movie_series_id= $id";
            $rs = $this->db->Execute($query, $aParams);
            $movieSeries = $this->completar($rs->GetRowAssoc(false));

            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' getId '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        
        return $movieSeries;
    }
    public function searchByTitle($title) {
        try {
            //$this->db->debug = true;
            $aParams = array();
            $arrayList = array();
            $query = "SELECT t.*, g.genre_desc, c.country_id FROM movie_series t JOIN genre g ON g.genre_id = t.genre_id JOIN origin o ON o.movie_series_id = t.movie_series_id JOIN country c ON o.country_id = c.country_id WHERE t.original_title LIKE '%$title%'";
            $rs = $this->db->Execute($query, $aParams);
            
             while (!$rs->EOF) {
                $movieSeries = $this->completar($rs->GetRowAssoc(false));
                $arrayList[] = $movieSeries;
                $rs->MoveNext();
            }
            $rs->Close();
            
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' searchByTitle '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        
        return $arrayList;
    }

    public function search($cantidad, $pagina) {
        $arrayList = array();
        try {
            $aParams = array();
            $sql = "SELECT t.*, g.genre_desc, c.country_id FROM movie_series t left JOIN genre g ON g.genre_id = t.genre_id left JOIN origin o ON o.movie_series_id = t.movie_series_id left JOIN country c ON o.country_id = c.country_id ";

            if ($pagina != NULL) {
                $rs = $this->db->SelectLimit($sql, $cantidad, ($pagina - 1) * $cantidad, $aParams);
            } else {
                $rs = $this->db->Execute($sql, $aParams);
            }
            while (!$rs->EOF) {
                $movieSeries = $this->completar($rs->GetRowAssoc(false));

                $arrayList[] = $movieSeries;
                $rs->MoveNext();
            }
            $rs->Close();
        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' search '";
            throw new DAODatabaseExecuteException($message, $adodb_exception->getCode());
        }
        return $arrayList;
    }

    public function update(MovieSeries $movieSeries) {
        //$this->db->debug = true;
        try {
            $aParams = array(
                'originalTitle' => $movieSeries->getOriginalTitle(),
                'genreId' => $movieSeries->getGenre()->getGenreId(),
                'movieSeriesFlag' => $movieSeries->getMovieSeriesFlag(),                
                'imdbLink' => $movieSeries->getImdbLink(),
                'year' => $movieSeries->getYear(),
                'movieImageLink' => $movieSeries->getMovieImageLink(),
                'trailerLink' => $movieSeries->getTrailerLink(),
                'seasons' => $movieSeries->getSeasons(),                
                'movieSeriesId' => $movieSeries->getMovieSeriesId(),
            );


            $rs = $this->db->Execute("UPDATE movie_series SET original_title = ?, genre_id = ?, movie_series_flag = ?, imdb_link = ?, year = ? , image_link = ?, trailer_link = ?, seasons = ? WHERE  movie_series_id = ?", $aParams);
            $rs->Close();

        } catch (ADODB_Exception $adodb_exception) {
            $message = "'[" . __CLASS__ . "] Error executing method ' update ' ";
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

    public function baja(MovieSeries $movieSeries) {
              /* todo --> remove unnecessary methods from interface */
    }

    public function delete(MovieSeries $movieSeries) {
        try{
            $query = "DELETE FROM movie_series WHERE movie_series_id=". $movieSeries->getMovieSeriesId();
            $rs = $this->db->Execute($query);
            $rs->Close();
        }
        catch(ADODB_Exception $adodb_exception){
            $message = "'[".__CLASS__."] Error when executing method  ' delete '";
            // Returns false if rollback fails
            if( !$this->rollbackTransaction() ){
                $message .= " Error performing ROLLBACK.";
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
            $sql = "SELECT COUNT(*) cantidad FROM movie_series t  WHERE 1=1 ";
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
    
    public function suggestByTitle($title) {
        try {
            //$this->db->debug = true;
            $aParams = array();
            $arrayList = array();
            $query = "SELECT t.*, g.genre_desc, c.country_id FROM movie_series t JOIN genre g ON g.genre_id = t.genre_id JOIN origin o ON o.movie_series_id = t.movie_series_id JOIN country c ON o.country_id = c.country_id WHERE t.original_title LIKE '%$title%' ";
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