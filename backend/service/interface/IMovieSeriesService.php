<?php

interface IMovieSeriesService {
    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function update(MovieSeries $movieSeries);
    public function insert(MovieSeries $movieSeries);
    public function delete(MovieSeries $movieSeries);
    public function suggestByTitle($title);
    public function searchByTitle($title);

}


?>