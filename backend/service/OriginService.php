<?php
include_once(SERVICE_INTERFACE . 'IOriginService.php');

class OriginService implements IOriginService {

   private $originDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->originDAO = DAOFactory::getDAO('origin');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('OriginService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('OriginService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('OriginService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('OriginService', $e->getMessage(), $e->getCode());
        }
    }

    public function delete($movieSeriesId) {
        try{
            return $this->originDAO->delete($movieSeriesId);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting Origin";
            throw new ServiceException('OriginService', $message, $e_dao_database->getCode());
        }
    }


    public function insert($movieSeriesId, $countryId) {
        try{
            return $this->originDAO->insert($movieSeriesId, $countryId);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred  while saving Origin";
            throw new ServiceException('OriginService', $message, $e_dao_database->getCode());
        }
    }

    public function update ($movieSeriesId, $countryId){
        try {
            return $this->originDAO->update($origin);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating Origin";
            throw new ServiceException('OriginService', $message, $e_dao_database->getCode());
        }
    }
    public function getId($id){
        try {
            return $this->originDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Origin id: $id";
            throw new ServiceException('OriginService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina){
        $array = array();
        try {
            $array = $this->originDAO->search($cantidad, $pagina);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing Origin";
            throw new ServiceException('OriginService', $message, $e_dao_database->getCode());
        }
        return $array;
    }

    function count(){
        try {
            return $this->originDAO->count();
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Origin records";
            throw new ServiceException('OriginService', $message, $e_dao_database->getCode());
        }
    }


}
?>
