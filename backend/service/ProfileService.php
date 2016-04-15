<?php
include_once(SERVICE_INTERFACE . 'IProfileService.php');

class ProfileService implements IProfileService {

   private $profileDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->profileDAO = DAOFactory::getDAO('profile');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('ProfileService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('ProfileService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('ProfileService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('ProfileService', $e->getMessage(), $e->getCode());
        }
    }




    public function delete(Profile $profile) {
        try{
            return $this->profileDAO->delete($profile);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting Profile";
            throw new ServiceException('ProfileService', $message, $e_dao_database->getCode());
        }
    }


    public function insert(Profile $profile) {
        try{
            return $this->profileDAO->insert($profile);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving Profile";
            throw new ServiceException('ProfileService', $message, $e_dao_database->getCode());
        }
    }

    public function update(Profile $profile) {
        try {
            return $this->profileDAO->update($profile);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating Profile";
            throw new ServiceException('ProfileService', $message, $e_dao_database->getCode());
        }
    }
    public function getId($id){
        try {
            return $this->profileDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Profile id: $id";
            throw new ServiceException('ProfileService', $message, $e_dao_database->getCode());
        }
    }
    
    public function getProfileDesc($id){
        try {
            return $this->profileDAO->getProfileDesc($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Profile description for id: $id";
            throw new ServiceException('ProfileService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina){
        $array = array();
        try {
            $array = $this->profileDAO->search($cantidad, $pagina);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing Profiles";
            throw new ServiceException('ProfileService', $message, $e_dao_database->getCode());
        }
        return $array;
    }

    function count(){
        try {
            return $this->profileDAO->count();
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Profile records";
            throw new ServiceException('ProfileService', $message, $e_dao_database->getCode());
        }
    }


}
?>
