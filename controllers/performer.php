<?php
require_once('../header.inc.php');

try{
    /* @var $performerService PerformerService */
    $performerService = ServiceFactory::getService('performer');
    /* @var $movieSeriesService MovieSeriesService */
    $movieSeriesService = ServiceFactory::getService('movieseries');
    /* @var $personService PersonService */
    $personService = ServiceFactory::getService('person');
}catch(ServiceException $e){
    echo $e->getMessage();
    exit;
}

$TAMANO_LIST=10;
$pagina = 1;
$controllerAction = (isset($_REQUEST['controllerAction']) ? $_REQUEST['controllerAction'] : "");

if(isset($_POST['idMovie'])){
    $idMovie = $_POST['idMovie'];
    $movie = $movieSeriesService->getId($idMovie);
}
$ver_listado=false;

switch ($controllerAction) {
	case "edit":
            if ($_REQUEST['id']!=""){
                /* @var $performer Performer */
                $performer = $performerService->getId($_REQUEST['id']);
                $resp = array ( 
                    'personId' => $performer->getPerson()->getPersonId(),
                    'performerLastname' => $performer->getPerson()->getPersonLastname(),
                    'performerFirstname' => $performer->getPerson()->getPersonFirstname(),
                    'performerType' => $performer->getPerformerType()
                );                  
                print json_encode($resp);
            }
            break;
	case "delete":
            if ($_REQUEST['id']!=""){
                try{
                    $performer = $performerService->getId($_REQUEST['id']);
                    $resultado = $performerService->delete($performer);
                    if ($resultado)
                        $resp = array ( 'result' => true);
                    else
                        $resp = array ( 'result' => false, 'error' => " .");
                    print json_encode($resp);

                } catch (ServiceException $e) {
                    $error = $e->getMessage();
                    $resp = array ( 'result' => false, 'error' => $error);
                    print json_encode($resp);
                }
            }
            break;
        case "save":
            if ($_POST['id']!="" && is_numeric($_POST['id']) && $_POST['id']  ){
                $performer = $performerService->getId($_POST['id']);
                $performer->setPerformerId($_POST['id']);
            }else{
                $performer = new Performer();
            }
            $performer->getMovieSeries()->setMovieSeriesId($_POST['idMovie']);
            $performer->setPerformerType($_POST['performerType']);
            $performer->getPerson()->setPersonId($_POST['personId']);
            try{
                if(empty($_REQUEST['id']) ){
                     $performerService->insert($performer);
                }else{
                    $performerService->update($performer);
                }
                $result = array('result' =>true);
                echo json_encode($result);
            } catch(Exception $e){
                $error = $e->getMessage();
                $result = array('result' =>false,'error'=>$error);
                echo json_encode($result);
            }
            break;
        case "listar":
            $pagina = null;
            $cantidad =null;
            $performerList = $performerService->search($cantidad,$pagina, $idMovie);
            $resultado = include(ROOT_PATH. "/views/performerList.php");
            break;
        default:
            $ver_listado=true;
}

if ($ver_listado){
    $personList = $personService->search(null, null);
    require_once (ROOT_PATH. "/views/performer.php");
}

?>