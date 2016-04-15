<?php

class Performer {
    private $performerId;
    private $movieSeries;
    private $person;
    private $performerType;

    function __construct() {
        $this->movieSeries = new MovieSeries();
        $this->person = new Person();
    }

    function getPerformerId() {
        return $this->performerId;
    }

    function setPerformerId($performerId) {
        $this->performerId = $performerId;
    }

    /**
     * @return MovieSeries
     */
    function getMovieSeries() {
        return $this->movieSeries;
    }

    function setMovieSeries($movieSeries) {
        $this->movieSeries = $movieSeries;
    }

    /**
     * @return Person
     */
    function getPerson() {
        return $this->person;
    }

    function setPerson($person) {
        $this->person = $person;
    }

    public function getPerformerType() {
        return $this->performerType;
    }

    public function setPerformerType($performerType) {
        $this->performerType = $performerType;
    }
}
?>