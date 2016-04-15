<?php
include_once(SERVICE_INTERFACE . 'IUserService.php');

class UserService implements IUserService {

   private $userDAO;
   private $userProfileDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->userDAO = DAOFactory::getDAO('user');
            $this->userProfileDAO = DAOFactory::getDAO('userprofile');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('UserService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('UserService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('UserService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('UserService', $e->getMessage(), $e->getCode());
        }
    }

    public function delete(User $user) {
        try{
            return $this->userDAO->delete($user);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting User";
            throw new ServiceException('UserService', $message, $e_dao_database->getCode());
        }
    }


    public function insert(User $user) {
        try{
            $return = 'ok';
            $userId = $this->userDAO->insert($user);            
            if(is_numeric($userId)){
                //assign profile to user
                $this->userProfileDAO->insert($userId, 2); // you cannot sign up as admin
            } else {
                $return = 'An error occurred while saving User';
                throw new ServiceException('UserService', $message, $e_dao_database->getCode());
            }
            return $return;
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving User";
            throw new ServiceException('UserService', $message, $e_dao_database->getCode());
        }
    }

    public function update(User $user) {
        try {
            return $this->userDAO->update($user);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating User";
            throw new ServiceException('UserService', $message, $e_dao_database->getCode());
        }
    }
    public function getId($id){
        try {
            return $this->userDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while  retrieving User id: $id";
            throw new ServiceException('UserService', $message, $e_dao_database->getCode());
        }
    }
    
    public function authenticate($username, $password){
        try {
            return $this->userDAO->authenticate($username, $password);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving User authenticating User id: $id";
            throw new ServiceException('UserService', $message, $e_dao_database->getCode());
        }
    }
    
    public function checkAvailableUser($username){
        try {
            return $this->userDAO->checkAvailableUser($username);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while checking for available username (alias: $username)";
            throw new ServiceException('UserService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina){
        $array = array();
        try {
            $array = $this->userDAO->search($cantidad, $pagina);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving listing Users";
            throw new ServiceException('UserService', $message, $e_dao_database->getCode());
        }
        return $array;
    }

    function count(){
        try {
            return $this->userDAO->count();
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting User records";
            throw new ServiceException('UserService', $message, $e_dao_database->getCode());
        }
    }


}
?>
