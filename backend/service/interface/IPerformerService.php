<?php
    interface IPerformerService {
        public function getId($id);
        public function count();
        public function search($cantidad, $pagina, $idMovie);
        public function update(Performer $performer);
        public function insert(Performer $performer);
        public function delete(Performer $performer);
        public function deleteCascading($movieSeriesId);
        public function checkCast($movieSeriesId);
    }
?>