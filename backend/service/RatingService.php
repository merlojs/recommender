<?php
include_once(SERVICE_INTERFACE . 'IRatingService.php');

class RatingService implements IRatingService {

   private $movieSeriesDAO;
   private $ratingDAO;
   
    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->ratingDAO = DAOFactory::getDAO('rating');
            $this->movieSeriesDAO = DAOFactory::getDAO('movieseries');
            
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('RatingService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('RatingService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('RatingService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('RatingService', $e->getMessage(), $e->getCode());
        }
    }
    
    /* No delete needed since it's community-basedd rating */


    public function save($rating) {
        try{
            return $this->ratingDAO->save($rating);                 
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving Rating";
            throw new ServiceException('RatingService', $message, $e_dao_database->getCode());
        }
    }
    
    
    public function getAllRatings($movieSeriesId){

        try {
            $array = $this->ratingDAO->getId($movieSeriesId);
            
            if(count($array) > 0){
                return $array;
            }
            
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving all Ratings For Movie/Series id: $movieSeriesId";
            throw new ServiceException('RatingService', $message, $e_dao_database->getCode());
        }
    }
    
    public function getTotalPoints($id){
        try {                      
            $avgRating = $this->ratingDAO->getTotalPoints($id);
            
            if(is_null($avgRating)){
                $avgRating = 0;
            } 
                        
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Ratings for Movie/Series id: $id";
            throw new ServiceException('RatingService', $message, $e_dao_database->getCode());
        }
        return $avgRating;
    }

    public function countVotes($movieSeriesId){
        try {
            return $this->ratingDAO->countVotes($movieSeriesId);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Rating records for Movie/Series id: $movieSeriesId";
            throw new ServiceException('RatingService', $message, $e_dao_database->getCode());
        }
    }
    
    function checkPreviousVote($userId, $movieSeriesId){
        try {
            return $this->ratingDAO->checkPreviousVote($userId, $movieSeriesId);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while verifying previous votes of $userId for Movie/Series: $movieSeriesId";
            throw new ServiceException('RatingService', $message, $e_dao_database->getCode());
        }
    }    
    
    

}
?>
