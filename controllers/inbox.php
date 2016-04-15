<?php
require_once('../header.inc.php');

try{
    /* @var $userMessageService UserMessageService */
    $userMessageService = ServiceFactory::getService('usermessage');
    
}catch(ServiceException $e){
    echo $e->getMessage();
    exit;
}

$TAMANO_LIST=10;
$pagina = 1;
$controllerAction = (isset($_REQUEST['controllerAction']) ? $_REQUEST['controllerAction'] : "");
$ver_listado=false;

$unreadMessageCount = $userMessageService->countUnreadMessages($_SESSION['userId']);

switch ($controllerAction) {
        case "listar":
            if  ( isset($_REQUEST['pagina']) &&  $_REQUEST['pagina']!="" && is_numeric($_REQUEST['pagina']) ){
                $pagina = $_REQUEST['pagina'];
            }
            $cantidad =$TAMANO_LIST;
            if ( isset($_REQUEST['cantidad']) && $_REQUEST['cantidad']!="" && is_numeric($_REQUEST['cantidad']) && $_REQUEST['cantidad']>0 ){
                    $cantidad = $_REQUEST['cantidad'];
            }
            $recipient = $_SESSION['userId'];
            $resultadosTotales = $userMessageService->count($recipient);
            $paginasTotales = ceil($resultadosTotales/$cantidad);
            if ($paginasTotales>0 && $pagina>$paginasTotales)
                $pagina=$paginasTotales;
            $desde= ($pagina -1) * $cantidad +1 ;
            $hasta= $desde + $cantidad -1;
            if ($hasta>$resultadosTotales)
                $hasta= $resultadosTotales;
            $userMessageList = $userMessageService->search($cantidad,$pagina, $recipient);
            $resultado = include(ROOT_PATH. "/views/inboxList.php");
            break;
        default:
            $ver_listado=true;
}

if ($ver_listado){
    require_once (ROOT_PATH. "/views/inbox.php");
}

?>