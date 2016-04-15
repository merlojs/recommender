<?php

/**
 * Description of ADODbException
 *
 */
class ADODbException extends Exception {
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return parent::__toString();
    }
}
?>
