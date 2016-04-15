<?php

/**
 * Description of DAOModelException
 */
class DAOModelException extends DAOException {
    public function __construct($dao, $message, $code) {
        parent::__construct($dao, $message, $code);

    }
    public function __toString() {
        return parent::__toString();
    }

}
?>
