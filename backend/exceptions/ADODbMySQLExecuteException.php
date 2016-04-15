<?php


/**
 * Description of ADODbMySQLExecuteException
 *
 */
class ADODbMySQLExecuteException extends ADODbException {
    public function __construct($message, $code) {
        parent::__construct($message, $code);

    }

    public function __toString() {
        return parent::__toString();
    }

}
?>
