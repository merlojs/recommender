<?php

interface IMovieSeriesUserDAO {
    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function update(MovieSeriesUser $movieSeriesUser);
    public function insert(MovieSeriesUser $movieSeriesUser);
    public function baja(MovieSeriesUser $movieSeriesUser);
    public function delete(MovieSeriesUser $movieSeriesUser);
}
?>