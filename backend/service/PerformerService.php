<?php
include_once(SERVICE_INTERFACE . 'IPerformerService.php');

class PerformerService implements IPerformerService {

   private $performerDAO;
   private $personDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->performerDAO = DAOFactory::getDAO('performer');
            $this->personDAO = DAOFactory::getDAO('person');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('PerformerService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('PerformerService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('PerformerService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('PerformerService', $e->getMessage(), $e->getCode());
        }
    }

    public function delete(Performer $performer) {
        try{
            return $this->performerDAO->delete($performer);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting Performer";
            throw new ServiceException('PerformerService', $message, $e_dao_database->getCode());
        }
    }
    
    public function deleteCascading($moviSeriesId) {
        try{
            return $this->performerDAO->deleteCascading($moviSeriesId);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting cascading Performers";
            throw new ServiceException('PerformerService', $message, $e_dao_database->getCode());
        }
    }

    public function insert(Performer $performer) {
        try{
            return $this->performerDAO->insert($performer);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving Performer";
            throw new ServiceException('PerformerService', $message, $e_dao_database->getCode());
        }
    }

    public function update(Performer $performer) {
        try {
            return $this->performerDAO->update($performer);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating Performer";
            throw new ServiceException('PerformerService', $message, $e_dao_database->getCode());
        }
    }
    public function getId($id){
        try {
            return $this->performerDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Performer id: $id";
            throw new ServiceException('PerformerService', $message, $e_dao_database->getCode());
        }
    }
    
    public function checkCast($movieSeriesId){
        try {
            return $this->performerDAO->checkCast($movieSeriesId);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Cast for movie series id: $movieSeriesId";
            throw new ServiceException('PerformerService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina, $idMovie){
        $array = array();
        try {
            $array = $this->performerDAO->search($cantidad, $pagina, $idMovie);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing Performers";
            throw new ServiceException('PerformerService', $message, $e_dao_database->getCode());
        }
        return $array;
    }
    
    public function searchByPerformerType($movieSeriesId, $performerType){
        $array = array();
        try {
            $array = $this->personDAO->searchByPerformerType($movieSeriesId, $performerType);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while searching by Performer Type";
            throw new ServiceException('PerformerService', $message.$e_dao_database->getCode(), $e_dao_database->getCode());
        }
        return $array;
    }
    
    function count(){
        try {
            return $this->performerDAO->count();
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Performer records";
            throw new ServiceException('PerformerService', $message, $e_dao_database->getCode());
        }
    }


}
?>
