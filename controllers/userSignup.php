<?php

require_once('../header.inc.php');

try {
    /* @var $userService UserService */
    $userService = ServiceFactory::getService('user');
    
} catch (ServiceException $e) {
    echo $e->getMessage();
    exit;
}

$TAMANO_LIST = 10;
$pagina = 1;
$controllerAction = (isset($_REQUEST['controllerAction']) ? $_REQUEST['controllerAction'] : "");
$ver_listado = false;

switch ($controllerAction) {
    case "save":
        $user = new User();
        $profile = new Profile();
        
        /* No user can sign up as admin, it makes no sense */
        
        $profile->setProfileId(2);   
        $profile->setProfileDesc('user');
        $profile->setProfileCode('user');
        
        $user->setUsername($_REQUEST['username']);
        $user->setPassword($_REQUEST['password']);
        $user->setUserLastname($_REQUEST['userLastname']);
        $user->setUserFirstname($_REQUEST['userFirstname']);       
        $user->setUserEnabledFlag(1);       
        $user->setProfile($profile);
        try {
            $return = $userService->insert($user);
            $result = array('result' => true);
            echo json_encode($result);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $result = array('result' => false, 'error' => $error);
            echo json_encode($result);
        }
        break;
    case "checkAvailableUser":
        if (isset($_POST['username']) && $_POST['username'] != "") {
            $username = ($_POST['username']);
            $result = $userService->checkAvailableUser($username);
            if($result instanceof User){
                $resp = 'NO';
            } else {
                $resp = 'SI';
            }
            print $resp;
        }

        break;
    default:
        $ver_listado = true;
}

if ($ver_listado) {

    require_once (ROOT_PATH . "/views/userSignup.php");
    
}
?>