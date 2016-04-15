<?php 
if (file_exists(ROOT_PATH.'/backend/config/configDB.ini')){
    header('location: controllers/index.php');
    exit;
} else {
    header('location: controllers/setup.php');
}

?>
