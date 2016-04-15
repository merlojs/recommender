<?php

/**
 * Description of DAODatabaseExecuteException
 *
 */
class DAODatabaseExecuteException extends DAOException {
    public function __construct($message, $code) {
        parent::__construct('ADODb', $message, $code);
    }

    public function __toString() {
        return parent::__toString();
    }

}
?>
