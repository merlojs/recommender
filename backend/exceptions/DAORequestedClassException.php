<?php
/**
 * Description of DAORequestedClassException
 */
class DAORequestedClassException extends DAOException {
    public function __construct($dao, $message, $code) {
        if(empty($message)) $message = "Class [ ".$dao.' ] DOES NOT EXIST.';
        
        parent::__construct($dao, $message, $code);
    }

    public function __toString() {
        return "Class [ ".parent::getDAOClass().' ] DOES NOT EXIST.';
    }

}
?>
