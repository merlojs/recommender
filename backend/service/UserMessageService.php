<?php
include_once(SERVICE_INTERFACE . 'IUserMessageService.php');

class UserMessageService implements IUserMessageService {

   private $userMessageDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->userMessageDAO = DAOFactory::getDAO('userMessage');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('UserMessageService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('UserMessageService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('UserMessageService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('UserMessageService', $e->getMessage(), $e->getCode());
        }
    }


    public function delete(UserMessage $userMessage) {
        try{
            return $this->userMessageDAO->delete($userMessage);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting User Message";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
    }


    public function insert(UserMessage $userMessage) {
        try{
            return $this->userMessageDAO->insert($userMessage);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving User Message";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
    }

    public function update(UserMessage $userMessage) {
        try {
            return $this->userMessageDAO->update($userMessage);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating User Message";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
    }
    
    public function markAsRead(UserMessage $userMessage) {
        try {
            return $this->userMessageDAO->markAsRead($userMessage);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while markAsRead User Message";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
    }
    
    public function getId($id){
        try {
            return $this->userMessageDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving User Message id: $id";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina, $recipient = null){
        $array = array();
        try {
            $array = $this->userMessageDAO->search($cantidad, $pagina, $recipient);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing User Messages";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
        return $array;
    }
    
    public function previewSearch($cantidad, $pagina, $recipient = null){
        $array = array();
        try {
            $array = $this->userMessageDAO->previewSearch($cantidad, $pagina, $recipient);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing User Messages Preview";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
        return $array;
    }

    function count($recipient = null){
        try {
            return $this->userMessageDAO->count($recipient);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting User Messages records";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
    }
    
    function countUnreadMessages($recipientId = null){        
        try {
            return $this->userMessageDAO->countUnreadMessages($recipientId);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Unread Messages for user id: $recipientId";
            throw new ServiceException('UserMessageService', $message, $e_dao_database->getCode());
        }
    }
    
    


}
?>
