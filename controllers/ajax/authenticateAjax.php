<?php
require_once('../../header.inc.php');

try {
    /* @var $userService UserService */
    $userService = ServiceFactory::getService('user');
    /* @var $profileService ProfileService */
    $profileService = ServiceFactory::getService('profile');
    
} catch (ServiceException $e) {
    echo $e->getMessage();
    exit;
}
$controllerAction = (isset($_POST['controllerAction']) ? $_POST['controllerAction'] : "");
switch ($controllerAction) {
    case "validate":
        if (($_POST['loginUsername'] != "") && ($_POST['loginPassword'] != "")) {
            $username = $_POST['loginUsername'];
            $password = $_POST['loginPassword'];           
            
            /* @var $user User */
            $user = $userService->authenticate($username, $password);           
            //$profileId = $user->getProfile()->getProfileId();              
            /* @var $profile Profile */
            //$profile = $profileService->getProfileDesc($profileId);
            //$user->getProfile()->setProfileDesc($profile->getProfileDesc());
            if($user instanceof User){
                
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['userId'] = $user->getUserId();
                $_SESSION['username'] = $user->getUsername();                
                $_SESSION['profile'] = $user->getProfile()->getProfileDesc(); 
                $_SESSION['userDesc'] = $user->getProfile()->getProfileDesc();
                
                echo 'ok';
            } else {
                echo 'ko';
            }
        } else {
            echo 'ko';
        }
    break;
}
?>