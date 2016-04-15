<?php

class Origin {

    private $movieSeries;
    private $country;

    function __construct() {
        $this->movieSeries = new MovieSeries();
        $this->country = new Country();
    }

    /**
     * @return MovieSeries
     */
    
    function getMovieSeries() {
        return $this->movieSeries;
    }

    function setMovieSeries(MovieSeries $movieSeries) {
        $this->movieSeries = $movieSeries;
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

}

?>
