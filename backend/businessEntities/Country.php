<?php
class Country
{

	 private $countryId;
	 private $countryDesc;


	function __construct() {
	
	}

	public function setCountryId( $countryId) {
		$this->countryId = $countryId;
	}

	public function getCountryId() {
		return $this->countryId;
	}
	public function setCountryDesc( $countryDesc) {
		$this->countryDesc = $countryDesc;
	}

	public function getCountryDesc() {
		return $this->countryDesc;
	}


}


?>
