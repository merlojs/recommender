<?php
include_once(SERVICE_INTERFACE . 'IGenreService.php');

class GenreService implements IGenreService {

   private $genreDAO;

    /**
     * @throws ServiceDatabaseException, ServiceRequestedClassException, ServiceException
     */
    function __construct() {
        try{
            $this->genreDAO = DAOFactory::getDAO('genre');
        } catch(DAOConfigFileException $e_dao_configfile){
            throw new ServiceConfigFileException('GenreService', $e_dao_configfile->getMessage(), $e_dao_configfile->getCode());
        } catch(DAODatabaseException $e_dao_database){
            throw new ServiceDatabaseException('GenreService', $e_dao_database->getMessage(), $e_dao_database->getCode());
        }
        catch(DAORequestedClassException $e_dao_requested_class){
            throw new ServiceRequestedClassException('GenreService', $e_dao_requested_class->getMessage(), $e_dao_requested_class->getCode());
        }
        catch(DAOException $e){
            throw new ServiceException('GenreService', $e->getMessage(), $e->getCode());
        }
    }




    public function delete(Genre $genre) {
        try{
            return $this->genreDAO->delete($genre);
        }
        catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while deleting Genre";
            throw new ServiceException('GenreService', $message, $e_dao_database->getCode());
        }
    }


    public function insert(Genre $genre) {
        try{
            return $this->genreDAO->insert($genre);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while saving Genre";
            throw new ServiceException('GenreService', $message, $e_dao_database->getCode());
        }
    }

    public function update(Genre $genre) {
        try {
            return $this->genreDAO->update($genre);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while updating el Genre";
            throw new ServiceException('GenreService', $message, $e_dao_database->getCode());
        }
    }
    public function getId($id){
        try {
            return $this->genreDAO->getId($id);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while retrieving Genre id: $id";
            throw new ServiceException('GenreService', $message, $e_dao_database->getCode());
        }
    }

    public function search($cantidad, $pagina){
        $array = array();
        try {
            $array = $this->genreDAO->search($cantidad, $pagina);
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while listing Genres";
            throw new ServiceException('GenreService', $message, $e_dao_database->getCode());
        }
        return $array;
    }

    function count(){
        try {
            return $this->genreDAO->count();
        } catch(DAODatabaseExecuteException $e_dao_database){
            $message = "An error occurred while counting Genre records";
            throw new ServiceException('GenreService', $message, $e_dao_database->getCode());
        }
    }


}
?>
