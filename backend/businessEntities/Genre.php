<?php
class Genre
{

	 private $genreId;
	 private $genreDesc;


	function __construct() {
	
	}

	public function setGenreId( $genreId) {
		$this->genreId = $genreId;
	}

	public function getGenreId() {
		return $this->genreId;
	}
	public function setGenreDesc( $genreDesc) {
		$this->genreDesc = $genreDesc;
	}

	public function getGenreDesc() {
		return $this->genreDesc;
	}


}


?>
