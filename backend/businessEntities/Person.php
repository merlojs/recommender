<?php

class Person {

    private $personId;
    private $personLastname;
    private $personFirstname;
    private $country;
    private $birthDate;
    private $personImageLink;

    function __construct() {
        $this->country = new Country();
    }

    public function setPersonId($personId) {
        $this->personId = $personId;
    }

    public function getPersonId() {
        return $this->personId;
    }

    public function setPersonLastname($personLastname) {
        $this->personLastname = $personLastname;
    }

    public function getPersonLastname() {
        return $this->personLastname;
    }

    public function setPersonFirstname($personFirstname) {
        $this->personFirstname = $personFirstname;
    }

    /**
     * @return Country
     */
    function getCountry() {
        return $this->country;
    }

    function setCountry(Country $country) {
        $this->country = $country;
    }

    public function getPersonFirstname() {
        return $this->personFirstname;
    }

    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function setPersonImageLink($personImageLink) {
        $this->personImageLink = $personImageLink;
    }

    public function getPersonImageLink() {
        return $this->personImageLink;
    }

}

?>
