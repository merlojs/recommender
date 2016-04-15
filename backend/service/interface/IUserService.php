<?php

interface IUserService {
    public function getId($id);
    public function count();
    public function search($cantidad, $pagina);
    public function update(User $user);
    public function insert(User $user);
    public function delete(User $user);

}


?>