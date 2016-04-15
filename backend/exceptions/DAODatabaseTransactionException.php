<?php

/**
 * Description of DAODatabaseTransactionException
 */
class DAODatabaseTransactionException extends DAOException {
    public function __construct($dao, $message, $code) {
        parent::__construct($dao, $message, $code);
    }

    public function __toString() {
        return parent::__toString();
    }

}
?>
