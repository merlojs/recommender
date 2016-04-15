<?php

require_once('../../header.inc.php');

try {
    /* @var $ratingService RatingService */
    $ratingService = ServiceFactory::getService('rating');
} catch (ServiceException $e) {
    echo $e->getMessage();
    exit;
}


$controllerAction = (isset($_REQUEST['controllerAction']) ? $_REQUEST['controllerAction'] : "");
switch ($controllerAction) {
    case "fetch":
        if ($_REQUEST['movieSeriesId'] != "" && $_REQUEST['widget_id'] != "") {
            $movieSeriesId = $_REQUEST['movieSeriesId'];
            $widgetId = $_REQUEST['widget_id'];
                        
            /* @var $rating Rating */
            
            $totalPoints = $ratingService->getTotalPoints($movieSeriesId);
            $votesCast = $ratingService->countVotes($movieSeriesId);
            if ($totalPoints != 0){
                $dec_avg = round($totalPoints / $votesCast, 1 );
                $whole_avg = round($dec_avg);
            } else {
                $dec_avg = 0;
                $whole_avg = 0;
            }
            $movieRating = array('widget_id' => $widgetId,
                'number_votes' => $votesCast,
                'total_points' => $totalPoints,
                'whole_avg' => $whole_avg,
                'dec_avg' => $dec_avg
                );
                     
                
            
            print json_encode($movieRating);
            
        } else {
            echo 'ko';
        }
        break;
    case "saveRating":
        if ($_REQUEST['movieSeriesId'] != "" && $_REQUEST['widget_id'] != "") {
            $movieSeriesId = $_REQUEST['movieSeriesId'];
            $widgetId = $_REQUEST['widget_id'];
            $userId = $_SESSION['userId'];            
            // Get the value of the vote
            preg_match('/star_([1-5]{1})/', $_REQUEST['clicked_on'], $match);
             $vote = $match[1];            
            
             /* @var $rating Rating */             
             
            if ($ratingService->checkPreviousVote($userId, $movieSeriesId) != 0){  
                /* cannot vote more than once, return result with error message */ 
                $result = 'voted';
            } else {
                $rating = new Rating();
                $rating->getUser()->setUserId($userId);
                $rating->getMovieSeries()->setMovieSeriesId($movieSeriesId);
                $rating->setRatingScore($vote);

                $result = $ratingService->save($rating);                                
            }
            print json_encode($result);
            
        } else {
            echo 'ko';
        }
        break;
        
}
?>  