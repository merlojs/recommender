<?php

require_once('../header.inc.php');

try {
    /* @var $userService UserService */
    $userService = ServiceFactory::getService('user');
    
    /* @var $profileService ProfileService */
    $userService = ServiceFactory::getService('profile');

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
            $user = $userService->getId($_REQUEST['id']);
            $resp = array('id' => $user->getUserId(),
                'Username' => $user->getUsername(),
                'Password' => $user->getPassword(),
                'UserLastname' => $user->getUserLastname(),
                'UserFirstname' => $user->getUserFirstname(),
                'UserCreationDate' => $user->getUserCreationDate(),
                'UserEnabledFlag' => $user->getUserEnabledFlag(),
                'UserModificationDate' => $user->getUserModificationDate(),
            );
            print json_encode($resp);
        }
        break;
    case "delete":
        if ($_REQUEST['id'] != "") {
            try {
                $user = $userService->getId($_REQUEST['id']);
                $resultado = $userService->delete($user);
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
            $user = $userService->getId($_REQUEST['id']);
        } else {
            $user = new User();
        }
        $user->setUsername($_REQUEST['username']);
        $user->setPassword($_REQUEST['password']);
        $user->setUserLastname($_REQUEST['userLastname']);
        $user->setUserFirstname($_REQUEST['userFirstname']);       
        $user->setUserEnabledFlag(1);

        try {
            if (empty($_REQUEST['id'])) {
                $userService->insert($user);
            } else {
                $userService->update($user);
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
        $resultadosTotales = $userService->count();
        $paginasTotales = ceil($resultadosTotales / $cantidad);
        if ($paginasTotales > 0 && $pagina > $paginasTotales)
            $pagina = $paginasTotales;
        $desde = ($pagina - 1) * $cantidad + 1;
        $hasta = $desde + $cantidad - 1;
        if ($hasta > $resultadosTotales)
            $hasta = $resultadosTotales;
        $userList = $userService->search($cantidad, $pagina);
        $resultado = include(ROOT_PATH . "/views/userList.php");
        break;
    default:
        $ver_listado = true;
}

if ($ver_listado) {

    require_once (ROOT_PATH . "/views/user.php");
}
?>