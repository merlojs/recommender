<?php
/* CONSTANTS */

if(!defined('DS')){
    define('DS', '/');
}

if(!defined('SITE_NAME')){
    define('SITE_NAME', 'recommender');
}
 
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].DS.SITE_NAME);
}
//define('VISTA', basename($_SERVER['PHP_SELF'], '.php'));
define('DAO_INTERFACE', ROOT_PATH. '/backend/dao/interface/');
define('SERVICE_INTERFACE', ROOT_PATH. '/backend/service/interface/');

/* INCLUDES */
require_once(ROOT_PATH . '/backend/service/ServiceFactory.php');


/* AUTOLOAD
 *  Loads only entity classes.
 *  All other tasks are performed in the files in /backend/config/
 *  configBD.ini, configDAO.ini and configService.ini
 * @param String $class_name
 */
function __autoload($class_name) {
    // Serach entities in bussinessEntities directory
    if (file_exists(ROOT_PATH . '/backend/businessEntities/' . $class_name . '.php')) {
        require_once(ROOT_PATH . '/backend/businessEntities/' . $class_name . '.php');
    }
}


if (!isset($_SESSION)) {
    session_start();
}
?>
