<?php
include_once(SERVICE_INTERFACE . 'IPersonService.php');

class PersonService implements IPersonService {

   private $personDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->personDAO = DAOFactory::getDAO('person');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('PersonService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('PersonService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('PersonService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('PersonService', $e->getMessage(), $e->getCode());
        }
    }

    public function delete(Person $person) {
        try{
            return $this->personDAO->delete($person);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting Person";
            throw new ServiceException('PersonService', $message, $e_dao_database->getCode());
        }
    }


    public function insert(Person $person) {
        try{
            return $this->personDAO->insert($person);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "Error al intentar guardar el Person";
            throw new ServiceException('PersonService', $message, $e_dao_database->getCode());
        }
    }

    public function update(Person $person) {
        try {
            return $this->personDAO->update($person);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating Person";
            throw new ServiceException('PersonService', $message, $e_dao_database->getCode());
        }
    }
    public function getId($id){
        try {
            return $this->personDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Person id: $id";
            throw new ServiceException('PersonService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina){
        $array = array();
        try {
            $array = $this->personDAO->search($cantidad, $pagina);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing Persons";
            throw new ServiceException('PersonService', $message, $e_dao_database->getCode());
        }
        return $array;
    }

    function count(){
        try {
            return $this->personDAO->count();
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Person records";
            throw new ServiceException('PersonService', $message, $e_dao_database->getCode());
        }
    }
    
    public function suggestByName($name){
        try {
            return $this->personDAO->suggestByName($name);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Person with name like name: $name";
            throw new ServiceException('PersonService', $message, $e_dao_database->getCode());
        }
    }
    
    public function searchByName($name){
        try {
            return $this->personDAO->searchByName($name);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Person title: $name";
            throw new ServiceException('PersonService', $message, $e_dao_database->getCode());
        }
    }


}
?>
