<?php

/**
 * Description of ServiceRequestedClassException
 */
class ServiceRequestedClassException extends ServiceException{
    public function __construct($service, $message, $code) {
        parent::__construct($service, $message, $code);
    }

    public function __toString() {
        return parent::__toString();
    }

}
?>
