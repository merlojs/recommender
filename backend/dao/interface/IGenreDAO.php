<?php

interface IGenreDAO {
    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function update(Genre $genre);
    public function insert(Genre $genre);
    public function baja(Genre $genre);
    public function delete(Genre $genre);
}
?>