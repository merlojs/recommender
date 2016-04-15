<?php
include_once(SERVICE_INTERFACE . 'ICountryService.php');

class CountryService implements ICountryService {

   private $countryDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->countryDAO = DAOFactory::getDAO('country');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('CountryService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('CountryService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('CountryService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('CountryService', $e->getMessage(), $e->getCode());
        }
    }




    public function delete(Country $country) {
        try{
            return $this->countryDAO->delete($country);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting Country";
            throw new ServiceException('CountryService', $message, $e_dao_database->getCode());
        }
    }


    public function insert(Country $country) {
        try{
            return $this->countryDAO->insert($country);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving Country";
            throw new ServiceException('CountryService', $message, $e_dao_database->getCode());
        }
    }

    public function update(Country $country) {
        try {
            return $this->countryDAO->update($country);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating Country";
            throw new ServiceException('CountryService', $message, $e_dao_database->getCode());
        }
    }
    public function getId($id){
        try {
            return $this->countryDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Country id: $id";
            throw new ServiceException('CountryService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina){
        $array = array();
        try {
            $array = $this->countryDAO->search($cantidad, $pagina);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing Countries";
            throw new ServiceException('CountryService', $message, $e_dao_database->getCode());
        }
        return $array;
    }

    function count(){
        try {
            return $this->countryDAO->count();
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Country records";
            throw new ServiceException('CountryService', $message, $e_dao_database->getCode());
        }
    }


}
?>
