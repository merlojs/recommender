<?php

require_once('../header.inc.php');

try {
    /* @var $amenitiesService AmenitiesService */
    $userMessageService = ServiceFactory::getService('usermessage');

    // otras???
} catch (ServiceException $e) {
    echo $e->getMessage();
    exit;
}

$TAMANO_LIST = 10;
$pagina = 1;
$controllerAction = (isset($_REQUEST['controllerAction']) ? $_REQUEST['controllerAction'] : "");
$ver_listado = false;

switch ($controllerAction) {
    case "edit":
        if ($_REQUEST['id'] != "") {
            /* @var $amenity Amenities */
            $userMessage = $userMessageService->getId($_REQUEST['id']);
            $resp = array('id' => $userMessage->getMessageId(),
                'MessageDate' => $userMessage->getMessageDate(),
                'SenderId' => $userMessage->getSenderId(),
                'RecipientId' => $userMessage->getRecipientId(),
                'MessageText' => $userMessage->getMessageText(),
                'ReadFlag' => $userMessage->getReadFlag()
            );
            print json_encode($resp);
        }
        break;
    case "delete":
        if ($_REQUEST['id'] != "") {
            try {
                $userMessage = $userMessageService->getId($_REQUEST['id']);
                $resultado = $userMessageService->delete($userMessage);
                if ($resultado)
                    $resp = array('result' => true);
                else
                    $resp = array('false' => true, 'error' => " .");
                print json_encode($resp);
            } catch (ServiceException $e) {
                $error = $e->getMessage();
                $resp = array('result' => false, 'error' => $error);
                print json_encode($resp);
            }
        }
        break;
    case "save":
        if ($_REQUEST['id'] != "" && is_numeric($_REQUEST['id']) && $_REQUEST['id']) {
            $userMessage = $userMessageService->getId($_REQUEST['id']);
        } else {
            $userMessage = new UserMessage();
        }
        $userMessage->setMessageDate($_REQUEST['MessageDate']);
        $userMessage->setSenderId($_REQUEST['SenderId']);
        $userMessage->setRecipientId($_REQUEST['RecipientId']);
        $userMessage->setMessageText($_REQUEST['MessageText']);
        
        /* New message, read flag set to 0 */
        $userMessage->setReadFlag(0);
        
        try {
            if (empty($_REQUEST['id'])) {
                $userMessageService->insert($userMessage);
            } else {
                $userMessageService->update($userMessage);
            }
            $result = array('result' => true);
            echo json_encode($result);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $result = array('result' => false, 'error' => $error);
            echo json_encode($result);
        }
        break;
    case "listar":
        if (isset($_REQUEST['pagina']) && $_REQUEST['pagina'] != "" && is_numeric($_REQUEST['pagina'])) {
            $pagina = $_REQUEST['pagina'];
        }
        $cantidad = $TAMANO_LIST;
        if (isset($_REQUEST['cantidad']) && $_REQUEST['cantidad'] != "" && is_numeric($_REQUEST['cantidad']) && $_REQUEST['cantidad'] > 0) {
            $cantidad = $_REQUEST['cantidad'];
        }
        $resultadosTotales = $userMessageService->count();
        $paginasTotales = ceil($resultadosTotales / $cantidad);
        if ($paginasTotales > 0 && $pagina > $paginasTotales)
            $pagina = $paginasTotales;
        $desde = ($pagina - 1) * $cantidad + 1;
        $hasta = $desde + $cantidad - 1;
        if ($hasta > $resultadosTotales)
            $hasta = $resultadosTotales;
        $userMessageList = $userMessageService->search($cantidad, $pagina);
        $resultado = include(ROOT_PATH . "/views/userMessageList.php");
        break;
    case "previewList":
        if (isset($_REQUEST['pagina']) && $_REQUEST['pagina'] != "" && is_numeric($_REQUEST['pagina'])) {
            $pagina = $_REQUEST['pagina'];
        }
        $cantidad = $TAMANO_LIST;
        if (isset($_REQUEST['cantidad']) && $_REQUEST['cantidad'] != "" && is_numeric($_REQUEST['cantidad']) && $_REQUEST['cantidad'] > 0) {
            $cantidad = $_REQUEST['cantidad'];
        }
        $resultadosTotales = $userMessageService->count();
        $paginasTotales = ceil($resultadosTotales / $cantidad);
        if ($paginasTotales > 0 && $pagina > $paginasTotales)
            $pagina = $paginasTotales;
        $desde = ($pagina - 1) * $cantidad + 1;
        $hasta = $desde + $cantidad - 1;
        if ($hasta > $resultadosTotales)
            $hasta = $resultadosTotales;
        $userMessageList = $userMessageService->search($cantidad, $pagina);
        $resultado = include(ROOT_PATH . "/views/userMessageList.php");
        break;
    default:
        $ver_listado = true;
}

if ($ver_listado) {

    require_once (ROOT_PATH . "/views/userMessage.php");
}
?>