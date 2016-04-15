<?php

class Profile {

    private $profileId;
    private $profileDesc;
    private $profileCode;
    
    function __construct() {
        
    }

    function getProfileId() {
        return $this->profileId;
    }

    function getProfileDesc() {
        return $this->profileDesc;
    }

    function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    function setProfileDesc($profileDesc) {
        $this->profileDesc = $profileDesc;
    }
    
    function getProfileCode() {
        return $this->profileCode;
    }

    function setProfileCode($profileCode) {
        $this->profileCode = $profileCode;
    }

}
?>