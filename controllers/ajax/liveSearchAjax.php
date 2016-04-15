<?php

require_once('../../header.inc.php');

try {
    /* @var $userService UserService */
    $userService = ServiceFactory::getService('user');
    /* @var $profileService ProfileService */
    $profileService = ServiceFactory::getService('profile');
    /* @var $movieSeriesService MovieSeriesService */
    $movieSeriesService = ServiceFactory::getService('movieseries');
    /* @var $personService PersonService */
    $personService = ServiceFactory::getService('person');
    
} catch (ServiceException $e) {
    echo $e->getMessage();
    exit;
}
$controllerAction = (isset($_REQUEST['controllerAction']) ? $_REQUEST['controllerAction'] : "");
switch ($controllerAction) {
    case "titleSuggest":
        if ($_REQUEST['term'] != "") {
            $title = $_REQUEST['term'];

            /* @var $movieSeries MovieSeries */
            $movieSeriesList = $movieSeriesService->suggestByTitle($title);
            $availableMovies = array();

            foreach ($movieSeriesList as $movieSeries) {
                array_push($availableMovies, $movieSeries->getOriginalTitle());                             
            }
            
            print json_encode($availableMovies);
            
        } else {
            echo 'ko';
        }
        break;
    case "nameSuggest":
        if ($_REQUEST['term'] != "") {
            $name = $_REQUEST['term'];

            /* @var $person Person */
            $personList = $personService->suggestByName($name);
            $availablePersons = array();

            foreach ($personList as $person) {
                array_push($availablePersons, $person->getPersonLastname().', '.$person->getPersonFirstname());                             
            }
            
            print json_encode($availablePersons);
            
        } else {
            echo 'ko';
        }
        break;
        
}
?>  