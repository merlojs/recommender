<?php

interface IPersonDAO {
    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function update(Person $person);
    public function insert(Person $person);
    public function delete(Person $person);
    public function suggestByName($name);
    public function searchByName($name);

}
?>