<?php
/**
 * Description of DAOException
 */

class DAOException extends Exception {
    private $DAOClass;

    function  __construct($dao, $message, $code) {
        parent::__construct($message, $code);
        $this->DAOClass = $dao;
    }
    public function __toString() {
        return __CLASS__.' - '.$this->getDAOClass().": [ ".$this->getCode().' ] '.$this->getMessage();
    }
    public function getDAOClass(){
        return $this->DAOClass;
    }
}
?>
