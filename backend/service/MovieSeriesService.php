<?php
include_once(SERVICE_INTERFACE . 'IMovieSeriesService.php');

class MovieSeriesService implements IMovieSeriesService {

   private $movieSeriesDAO;
   private $personDAO;
   private $originDAO;
   /**
    *  @var 
    * PerformerDAO 
    */
   private $performerDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->movieSeriesDAO = DAOFactory::getDAO('movieSeries');
            $this->personDAO = DAOFactory::getDAO('person');
            $this->originDAO = DAOFactory::getDAO('origin');
            $this->performerDAO = DAOFactory::getDAO('performer');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('MovieSeriesService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('MovieSeriesService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('MovieSeriesService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('MovieSeriesService', $e->getMessage(), $e->getCode());
        }
    }

    public function delete(MovieSeries $movieSeries) {
        try{
            if($this->originDAO->delete($movieSeries->getMovieSeriesId()) > 0){
                return $this->movieSeriesDAO->delete($movieSeries);
            } else {           
                throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
            }
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting Movie/Series";
            throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
        }
    }
    
    public function deleteCascading(MovieSeries $movieSeries) {
        try{
                        
            /* delete from performer if applicable*/
            $this->originDAO->startTransaction();
                $this->originDAO->delete($movieSeries->getMovieSeriesId());
                $cast = $this->performerDAO->checkCast($movieSeries->getMovieSeriesId());
                if(count($cast) > 0){
                    $this->performerDAO->deleteCascading($movieSeries->getMovieSeriesId());
                }
                $return = $this->movieSeriesDAO->delete($movieSeries);
            $this->originDAO->endTransaction();
            if( $return > 0 ){
                return $return;
            } else {
                $message = "An error occurred while deleting Movie/Series";
                throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
            }
            
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting Movie/Series";
            throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
        }
    }


    public function insert(MovieSeries $movieSeries) {
        try{
            $return = 'ok';
            $movieSeriesId = $this->movieSeriesDAO->insert($movieSeries);            
            if(is_numeric($movieSeriesId)){
                //Insert into origin Table
                $this->originDAO->insert($movieSeriesId, $movieSeries->getCountry()->getCountryId());
            } else {
                $return = 'An error occurred while saving Movie/Series';
                throw new ServiceException('MovieSeries', $message, $e_dao_database->getCode());
            }
            return $return;               
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving Movie/Series";
            throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
        }
    }

    public function update(MovieSeries $movieSeries) {
        try {
            return $this->movieSeriesDAO->update($movieSeries);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating Movie/Series";
            throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
        }
    }
    
    public function getId($id){
        try {
            return $this->movieSeriesDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Movie/Series id: $id";
            throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
        }
    }
    
    public function searchByTitle($title){
        try {
            return $this->movieSeriesDAO->searchByTitle($title);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Movie/Series title: $title";
            throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina){
        $array = array();
        $array2 = array();
        try {
            $array = $this->movieSeriesDAO->search($cantidad, $pagina);
            if(count($array) > 0){
                foreach ($array as $movieSeries){
                    /* @var $movieSeries MovieSeries */
                    $actorList = $this->personDAO->searchByPerformerType($movieSeries->getMovieSeriesId(), 'A');
                    $movieSeries->setActorList($actorList);
                    $directorList = $this->personDAO->searchByPerformerType($movieSeries->getMovieSeriesId(), 'D');
                    $movieSeries->setDirectorList($directorList);
                    $array2[] = $movieSeries;
                }
            } else {
                $array2 = $array;                
            }
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing Movie/Series";
            throw new ServiceException('MovieSeriesService', $message.$e_dao_database->getCode(), $e_dao_database->getCode());
        }
        return $array2;
    }

    function count(){
        try {
            return $this->movieSeriesDAO->count();
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Movie/series records";
            throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
        }
    }
    
    public function suggestByTitle($title){
        try {
            return $this->movieSeriesDAO->suggestByTitle($title);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Movie/Series title: $title";
            throw new ServiceException('MovieSeriesService', $message, $e_dao_database->getCode());
        }
    }


}
?>
