<?php

interface ICountryService {
    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function update(Country $country);
    public function insert(Country $country);
    public function delete(Country $country);
    
    
}


?>