<?php

/**
 * Description of ADODbOracleConnectException
 */
class ADODbOracleConnectException extends ADODbException {
    public function __construct($message, $code) {
        parent::__construct($message, $code);

    }

    public function __toString() {
        return parent::__toString();
    }

}
?>
