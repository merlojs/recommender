<?php

/**
 * Description of ADODbOracleExecuteException
 *
 */
class ADODbOracleExecuteException extends ADODbException {
    
    public function __construct($message, $code) {
        parent::__construct($message, $code);

    }
    public function __toString() {
        return parent::__toString();
    }

}
?>
