<?php

class MovieSeries {

    private $movieSeriesId;
    private $originalTitle;
    private $genre;
    private $country;
    private $movieSeriesFlag;
    private $imdbLink;
    private $year;
    private $movieImageLink;
    private $trailerLink;
    private $seasons;
    private $actorList;
    private $directorList;

    function __construct() {
        $this->genre = new Genre();
        $this->country = new Country();
    }

    public function setMovieSeriesId($movieSeriesId) {
        $this->movieSeriesId = $movieSeriesId;
    }

    public function getMovieSeriesId() {
        return $this->movieSeriesId;
    }

    public function setOriginalTitle($originalTitle) {
        $this->originalTitle = $originalTitle;
    }

    public function getOriginalTitle() {
        return $this->originalTitle;
    }

    public function setGenre(Genre $genre) {
        $this->genre = $genre;
    }

    /**
     *  @return Genre 
     */
    public function getGenre() {
        return $this->genre;
    }
    
    /**
     *  @return Country
     */
    
    function getCountry() {
        return $this->country;
    }

    function setCountry(Country $country) {
        $this->country = $country;
    }

    
    public function setMovieSeriesFlag($movieSeriesFlag) {
        $this->movieSeriesFlag = $movieSeriesFlag;
    }

    public function getMovieSeriesFlag() {
        return $this->movieSeriesFlag;
    }    

    public function setImdbLink($imdbLink) {
        $this->imdbLink = $imdbLink;
    }

    public function getImdbLink() {
        return $this->imdbLink;
    }

    public function setYear($year) {
        $this->year = $year;
    }

    public function getYear() {
        return $this->year;
    }

    public function setMovieImageLink($movieImageLink) {
        $this->movieImageLink = $movieImageLink;
    }

    public function getMovieImageLink() {
        return $this->movieImageLink;
    }

    public function setTrailerLink($trailerLink) {
        $this->trailerLink = $trailerLink;
    }

    public function getTrailerLink() {
        return $this->trailerLink;
    }

    public function setSeasons($seasons) {
        $this->seasons = $seasons;
    }

    public function getSeasons() {
        return $this->seasons;
    }

    function getActorList() {
        return $this->actorList;
    }

    function getDirectorList() {
        return $this->directorList;
    }

    function setActorList($actorList) {
        $this->actorList = $actorList;
    }

    function setDirectorList($directorList) {
        $this->directorList = $directorList;
    }

}

?>