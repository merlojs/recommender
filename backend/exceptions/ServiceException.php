<?php

/**
 * Description of ServiceException
 */

class ServiceException extends Exception{
    private $serviceClass;

    public function __construct($service, $message, $code) {
        $this->serviceClass = $service;
        parent::__construct($message, $code);
    }
    public function __toString() {
        return __CLASS__.' - '.$this->getServiceClass().": [ ".$this->getCode().' ] '.$this->getMessage();
    }
    public function getServiceClass(){
        return $this->serviceClass;
    }

}
?>
