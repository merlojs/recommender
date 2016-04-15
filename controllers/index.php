<?php
    require_once('../header.inc.php');
    if (file_exists(ROOT_PATH.'/backend/config/configDB.ini')){
        $loggedIn = false;    
        if(!isset($_SESSION['profile'])){
            $_SESSION['userDesc'] = 'Guest';
            $_SESSION['profile'] = 'guest';
            $controllerAction = 'Log In';
        } else {
            $loggedIn = true;
            $controllerAction = 'Cerrar';
        }    

        try{
            /* @var $movieSeriesService MovieSeriesService */
            $movieSeriesService = ServiceFactory::getService('movieseries');
            /* @var $userService UserService */
            $userService = ServiceFactory::getService('user');
            /* @var $performerService PerformerService */
            $performerService = ServiceFactory::getService('performer');
            /* @var $userMessageService UserMessageService */
            $userMessageService = ServiceFactory::getService('usermessage');
            
            $movieSeriesList = $movieSeriesService->search(null, null);            
            $userList = $userService->search(null, null);

            $movieCount = $movieSeriesService->count();
            if(isset($_SESSION['userId'])){
                $unreadMessageCount = $userMessageService->countUnreadMessages($_SESSION['userId']);
            } 
            
        }catch(ServiceException $e){
            echo $e->getMessage();
            exit;
        }
        require_once (ROOT_PATH. "/views/index.php");
    } else {
        header('location: ../controllers/setup.php');
        exit;
    }
?>