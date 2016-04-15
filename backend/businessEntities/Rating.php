<?php

class Rating {
    
    private $ratingId;
    private $user;
    private $movieSeries;
    private $ratingScore;

    function __construct() {
        $this->user = new User();
        $this->movieSeries = new MovieSeries();
    }
    
    function getRatingId() {
        return $this->ratingId;
    }

    /**
    *  @return User
    */
    
    function getUser() {
        return $this->user;
    }
    
    /**
    *  @return MovieSeries
    */

    function getMovieSeries() {
        return $this->movieSeries;
    }

    function getRatingScore() {
        return $this->ratingScore;
    }
    
    function setRatingId($ratingId) {
        $this->ratingId = $ratingId;
    }

    function setUser(User $user) {
        $this->user = $user;
    }

    function setMovieSeries(MovieSeries $movieSeries) {
        $this->movieSeries = $movieSeries;
    }

    function setRatingScore($ratingScore) {
        $this->ratingScore = $ratingScore;
    }
    
}

?>