<?php

interface IUserMessageDAO {
    public function getId($id);
    public function count();
    public function countUnreadMessages($recipientId);
    public function search($cantidad, $pagina);
    public function update(UserMessage $userMessage);
    public function insert(UserMessage $userMessage);
    public function delete(UserMessage $userMessage);
    public function previewSearch($cantidad, $pagina, $recipientId);
}
?>