<?php

class UserMessage {

    private $messageId;
    private $messageDate;
    private $sender;
    private $recipient;
    private $messageText;
    private $movieSeries;
    private $readFlag;

    function __construct() {
        $this->sender = new User();
        $this->recipient = new User();
        $this->movieSeries = new MovieSeries();
    }

    public function setMessageId($messageId) {
        $this->messageId = $messageId;
    }

    public function getMessageId() {
        return $this->messageId;
    }

    public function setMessageDate($messageDate) {
        $this->messageDate = $messageDate;
    }

    public function getMessageDate() {
        return $this->messageDate;
    }

    /**
     * @return User
     */
    public function getSender() {
        return $this->sender;
    }

    public function setSender(User $sender) {
        $this->sender = $sender;
    }

    public function setRecipient(User $recipient) {
        $this->recipient = $recipient;
    }

    /**
     * @return User
     */
    public function getRecipient() {
        return $this->recipient;
    }

    public function setMessageText($messageText) {
        $this->messageText = $messageText;
    }

    public function getMessageText() {
        return $this->messageText;
    }
    
    /**
     * @return MovieSeries
     */
    
    function getMovieSeries() {
        return $this->movieSeries;
    }

    function setMovieSeries(MovieSeries $movieSeries) {
        $this->movieSeries = $movieSeries;
    }
    
    function getReadFlag() {
        return $this->readFlag;
    }

    function setReadFlag($readFlag) {
        $this->readFlag = $readFlag;
    }

}

?>
