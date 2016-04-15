<?php

class User {

    private $userId;
    private $username;
    private $password;
    private $userLastname;
    private $userFirstname;
    private $userCreationDate;
    private $userEnabledFlag;
    private $userModificationDate;
    private $profile;

    function __construct() {
        $this->profile = new Profile();
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setUserLastname($userLastname) {
        $this->userLastname = $userLastname;
    }

    public function getUserLastname() {
        return $this->userLastname;
    }

    public function setUserFirstname($userFirstname) {
        $this->userFirstname = $userFirstname;
    }

    public function getUserFirstname() {
        return $this->userFirstname;
    }

    public function setUserCreationDate($userCreationDate) {
        $this->userCreationDate = $userCreationDate;
    }

    public function getUserCreationDate() {
        return $this->userCreationDate;
    }

    public function setUserEnabledFlag($userEnabledFlag) {
        $this->userEnabledFlag = $userEnabledFlag;
    }

    public function getUserEnabledFlag() {
        return $this->userEnabledFlag;
    }

    public function setUserModificationDate($userModificationDate) {
        $this->userModificationDate = $userModificationDate;
    }

    public function getUserModificationDate() {
        return $this->userModificationDate;
    }
    
    /**
     * @return Profile
     */
    function getProfile() {
        return $this->profile;
    }
    
    function setProfile(Profile $profile) {
        $this->profile = $profile;
    }


}
?>