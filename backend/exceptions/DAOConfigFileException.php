<?php

/**
 * Description of DAOConfigFileException
 */
class DAOConfigFileException extends DAOException {
    public function __construct($dao, $message, $code) {
        parent::__construct($dao, $message, $code);

    }

    public function __toString() {
        return parent::__toString();
    }

}
?>
