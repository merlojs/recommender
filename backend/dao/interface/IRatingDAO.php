<?php

interface IRatingDAO {
    public function getTotalPoints($id);
    public function getAllRatings($movieSeriesId);
    public function countVotes($movieSeriesId);
    public function save($rating);
    public function checkPreviousVote($userId, $movieSeriesId);
}

