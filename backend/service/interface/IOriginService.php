<?php

interface IOriginService {

    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function update($movieSeriesId, $countryId);
    public function insert($movieSeriesId, $countryId);
    public function delete($movieSeriesId);
}

?>