<?php

interface IGenericDAO {
	public function startTransaction();
	public function endTransaction();
	public function rollbackTransaction();
	public function autoIncrement($sec);
}
?>
