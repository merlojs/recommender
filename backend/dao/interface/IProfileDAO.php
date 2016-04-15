<?php

interface IProfileDAO {
    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function update(Profile $profile);
    public function insert(Profile $profile);
    public function baja(Profile $profile);
    public function delete(Profile $profile);
}
?>