<?php

interface ICountryDAO {
    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function insert(Country $country);
    public function baja(Country $country);
}
?>