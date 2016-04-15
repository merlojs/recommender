<?php

require_once('../header.inc.php');

try {
    /* @var $movieSeriesService MovieSeriesService */
    $movieSeriesService = ServiceFactory::getService('movieseries');
    /* @var $genreService GenreService */
    $genreService = ServiceFactory::getService('genre');
    /* @var $performerService PerformerService */
    $performerService = ServiceFactory::getService('performer');
    /* @var $countryService CountryService */
    $countryService = ServiceFactory::getService('country');
    /* @var $originService OriginService */
    $originService = ServiceFactory::getService('origin');
    /* @var $personService PersonService */
    $personService = ServiceFactory::getService('person');
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
            /* @var $movieSeries MovieSeries */
            $movieSeries = $movieSeriesService->getId($_REQUEST['id']);
            $resp = array('id' => $movieSeries->getMovieSeriesId(),
                'OriginalTitle' => $movieSeries->getOriginalTitle(),
                'MovieSeriesFlag' => $movieSeries->getMovieSeriesFlag(),
                'GenreId' => $movieSeries->getGenre()->getGenreId(),
                'countryId' => $movieSeries->getCountry()->getCountryId(),
                'ImdbLink' => $movieSeries->getImdbLink(),
                'Year' => $movieSeries->getYear(),
                'MovieImageLink' => $movieSeries->getMovieImageLink(),
                'TrailerLink' => $movieSeries->getTrailerLink(),
                'Seasons' => $movieSeries->getSeasons()
            );

            print json_encode($resp);
        }
        break;
    case "titleSearch":
        if ($_REQUEST['title'] != "") {

            if (isset($_REQUEST['pagina']) && $_REQUEST['pagina'] != "" && is_numeric($_REQUEST['pagina'])) {
                $pagina = $_REQUEST['pagina'];
            }
            $cantidad = $TAMANO_LIST;
            if (isset($_REQUEST['cantidad']) && $_REQUEST['cantidad'] != "" && is_numeric($_REQUEST['cantidad']) && $_REQUEST['cantidad'] > 0) {
                $cantidad = $_REQUEST['cantidad'];
            }


            try {
                $movieSeriesList = $movieSeriesService->searchByTitle($_REQUEST['title']);
                $resp = array();

                if (count($movieSeriesList) > 0) {
                    foreach ($movieSeriesList as $movieSeries) {
                        if ($movieSeries) {
                            $resp[] = array('id' => $movieSeries->getMovieSeriesId(),
                                'OriginalTitle' => $movieSeries->getOriginalTitle(),
                                'MovieSeriesFlag' => $movieSeries->getMovieSeriesFlag(),
                                'GenreId' => $movieSeries->getGenre()->getGenreId(),
                                'countryId' => $movieSeries->getCountry()->getCountryId(),
                                'ImdbLink' => $movieSeries->getImdbLink(),
                                'Year' => $movieSeries->getYear(),
                                'MovieImageLink' => $movieSeries->getMovieImageLink(),
                                'TrailerLink' => $movieSeries->getTrailerLink(),
                                'Seasons' => $movieSeries->getSeasons()
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
            $resultadosTotales = count($movieSeriesList);
            $paginasTotales = ceil($resultadosTotales / $cantidad);
            if ($paginasTotales > 0 && $pagina > $paginasTotales)
                $pagina = $paginasTotales;
            $desde = ($pagina - 1) * $cantidad + 1;
            $hasta = $desde + $cantidad - 1;
            if ($hasta > $resultadosTotales)
                $hasta = $resultadosTotales;            
        }
        $resultado = include(ROOT_PATH . "/views/movieSeriesList.php");
        break;
    case "delete":
        if ($_REQUEST['id'] != "") {
            try {
                $movieSeries = $movieSeriesService->getId($_REQUEST['id']);
                //die('<pre>'.print_r($movieSeries, true).'</pre>');
                $resultado = $movieSeriesService->deleteCascading($movieSeries);
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
            $movieSeries = $movieSeriesService->getId($_REQUEST['id']);
        } else {
            $movieSeries = new MovieSeries();
        }


        $movieSeries->setOriginalTitle($_REQUEST['OriginalTitle']);
        $movieSeries->getGenre()->setGenreId($_REQUEST['GenreId']);
        $movieSeries->getCountry()->setCountryId($_REQUEST['countryId']);
        $movieSeries->setMovieSeriesFlag($_REQUEST['MovieSeriesFlag']);
        $movieSeries->setImdbLink($_REQUEST['ImdbLink']);
        $movieSeries->setYear($_REQUEST['Year']);
        $movieSeries->setMovieImageLink($_REQUEST['MovieImageLink']);
        $movieSeries->setTrailerLink($_REQUEST['TrailerLink']);
        $movieSeries->setSeasons($_REQUEST['Seasons']);

        try {
            if (empty($_REQUEST['id'])) {
                $movieSeriesService->insert($movieSeries);
            } else {
                $movieSeriesService->update($movieSeries);
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
        $resultadosTotales = $movieSeriesService->count();
        $paginasTotales = ceil($resultadosTotales / $cantidad);
        if ($paginasTotales > 0 && $pagina > $paginasTotales)
            $pagina = $paginasTotales;
        $desde = ($pagina - 1) * $cantidad + 1;
        $hasta = $desde + $cantidad - 1;
        if ($hasta > $resultadosTotales)
            $hasta = $resultadosTotales;

        $movieSeriesList = $movieSeriesService->search($cantidad, $pagina);

        foreach ($movieSeriesList as $movieSeries) {
            $actorList = $performerService->searchByPerformerType($movieSeries->getMovieSeriesId(), 'A');
            $directorList = $performerService->searchByPerformerType($movieSeries->getMovieSeriesId(), 'D');
        }

        $resultado = include(ROOT_PATH . "/views/movieSeriesList.php");
        break;
    default:
        $ver_listado = true;
}

if ($ver_listado) {
    $personList = $personService->search(null, null);
    $genreList = $genreService->search(null, null);
    $countryList = $countryService->search(null, null);
    require_once (ROOT_PATH . "/views/movieSeries.php");
}
?>