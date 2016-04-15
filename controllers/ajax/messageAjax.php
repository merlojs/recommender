<?php
require_once('../../header.inc.php');

try {
    /* @var $userMessageService UserMessageService */
    $userMessageService = ServiceFactory::getService('usermessage');
        
} catch (ServiceException $e) {
    echo $e->getMessage();
    exit;
}
$controllerAction = (isset($_REQUEST['action']) ? $_REQUEST['action'] : "");
switch ($controllerAction) {
    case "sendMessage":
        if (($_POST['message'] != "") && ($_POST['recipient'] != "") && ($_POST['movie'] != "")) {
            //var_dump($_POST);
            $userMessage = new UserMessage();
            $userMessage->setMessageText($_POST['message']);
            $userMessage->getRecipient()->setUserId($_POST['recipient']);
            $userMessage->getSender()->setUserId($_SESSION['userId']);
            $userMessage->getMovieSeries()->setMovieSeriesId($_POST['movie']);
            $user = $userMessageService->insert($userMessage);
            echo 'ok';
        } else {
            echo 'ko';
        }
    break;
    case "read":
        $messageId = $_POST['messageId'];
        $userMessage = $userMessageService->getId($messageId);
        $return = $userMessageService->markAsRead($userMessage);
        if($return){
            echo 'ok';
        } else {
            echo 'ko';
        }
    break;
}
?>