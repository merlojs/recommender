<?php

require_once('../header.inc.php');

try {
    /* @var $personService PersonService */
    $personService = ServiceFactory::getService('person');
    /* @var $countryService CountryService */
    $countryService = ServiceFactory::getService('country');
    /* @var $userMessageService UserMessageService */
    $userMessageService = ServiceFactory::getService('usermessage');
    
} catch (ServiceException $e) {
    echo $e->getMessage();
    exit;
}

$TAMANO_LIST = 10;
$pagina = 1;
$controllerAction = (isset($_REQUEST['controllerAction']) ? $_REQUEST['controllerAction'] : "");
$ver_listado = false;

$unreadMessageCount = $userMessageService->countUnreadMessages($_SESSION['userId']);

switch ($controllerAction) {
    case "edit":
        if ($_REQUEST['id'] != "") {
            /* @var $person Person */
            $person = $personService->getId($_REQUEST['id']);
            $resp = array('id' => $person->getPersonId(),
                'PersonLastname' => $person->getPersonLastname(),
                'PersonFirstname' => $person->getPersonFirstname(),
                'BirthDate' => $person->getBirthDate(),
                'PersonImageLink' => $person->getPersonImageLink(),
                'countryId' => $person->getCountry()->getCountryId()
            );
            print json_encode($resp);
        }
        break;
    case "delete":
        if ($_REQUEST['id'] != "") {
            try {
                $person = $personService->getId($_REQUEST['id']);
                $resultado = $personService->delete($person);
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
            $person = $personService->getId($_REQUEST['id']);
        } else {
            $person = new Person();
        }
        $person->setPersonLastname($_REQUEST['PersonLastname']);
        $person->setPersonFirstname($_REQUEST['PersonFirstname']);
        $person->setBirthDate($_REQUEST['BirthDate']);
        $person->setPersonImageLink($_REQUEST['PersonImageLink']);
        $person->getCountry()->setCountryId($_REQUEST['countryId']);

        try {
            if (empty($_REQUEST['id'])) {
                $personService->insert($person);
            } else {
                $personService->update($person);
            }
            $result = array('result' => true);
            echo json_encode($result);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $result = array('result' => false, 'error' => $error);
            echo json_encode($result);
        }
        break;
    case "nameSearch":
        if ($_REQUEST['name'] != "") {

            if (isset($_REQUEST['pagina']) && $_REQUEST['pagina'] != "" && is_numeric($_REQUEST['pagina'])) {
                $pagina = $_REQUEST['pagina'];
            }
            $cantidad = $TAMANO_LIST;
            if (isset($_REQUEST['cantidad']) && $_REQUEST['cantidad'] != "" && is_numeric($_REQUEST['cantidad']) && $_REQUEST['cantidad'] > 0) {
                $cantidad = $_REQUEST['cantidad'];
            }

            try {               
                /* search before comma to avoid issues */
                
                $personList = $personService->searchByName(current(explode(",", $_REQUEST['name'])));                
                $resp = array();

                if (count($personList) > 0) {
                    foreach ($personList as $person) {
                        if ($person) {
                            $resp[] = array('personId' => $person->getPersonId(),
                                'personLastname' => $person->getPersonLastname(),
                                'personFirstName' => $person->getPersonFirstname(),
                                'countryId' => $person->getCountry()->getCountryId(),
                                'birthDate' => $person->getBirthDate(),
                                'imageLink' => $person->getPersonImageLink()                                  
                            );
                        }
                    }
                } else {
                    $resp = array('false' => true, 'error' => " .");
                }
            } catch (ServiceException $e) {
                $error = $e->getMessage();
                $resp = array('result' => false, 'error' => $error);
            }            
            $resultadosTotales = count($personList);
            $paginasTotales = ceil($resultadosTotales / $cantidad);
            if ($paginasTotales > 0 && $pagina > $paginasTotales)
                $pagina = $paginasTotales;
            $desde = ($pagina - 1) * $cantidad + 1;
            $hasta = $desde + $cantidad - 1;
            if ($hasta > $resultadosTotales)
                $hasta = $resultadosTotales;            
        }
        $resultado = include(ROOT_PATH . "/views/personList.php");
        break;
    case "listar":
        if (isset($_REQUEST['pagina']) && $_REQUEST['pagina'] != "" && is_numeric($_REQUEST['pagina'])) {
            $pagina = $_REQUEST['pagina'];
        }
        $cantidad = $TAMANO_LIST;
        if (isset($_REQUEST['cantidad']) && $_REQUEST['cantidad'] != "" && is_numeric($_REQUEST['cantidad']) && $_REQUEST['cantidad'] > 0) {
            $cantidad = $_REQUEST['cantidad'];
        }
        $resultadosTotales = $personService->count();
        $paginasTotales = ceil($resultadosTotales / $cantidad);
        if ($paginasTotales > 0 && $pagina > $paginasTotales)
            $pagina = $paginasTotales;
        $desde = ($pagina - 1) * $cantidad + 1;
        $hasta = $desde + $cantidad - 1;
        if ($hasta > $resultadosTotales)
            $hasta = $resultadosTotales;
        $personList = $personService->search($cantidad, $pagina);
        $resultado = include(ROOT_PATH . "/views/personList.php");
        break;
    default:
        $ver_listado = true;
}

if ($ver_listado) {
    $countryList = $countryService->search(null, null);
    require_once (ROOT_PATH . "/views/person.php");
}
?>