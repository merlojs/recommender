<?php

//  Load Service exceptions required handle service errors
require_once(ROOT_PATH . '/backend/exceptions/ServiceException.php');
require_once(ROOT_PATH . '/backend/exceptions/ServiceConfigFileException.php');
require_once(ROOT_PATH . '/backend/exceptions/ServiceDatabaseException.php');
require_once(ROOT_PATH . '/backend/exceptions/ServiceRequestedClassException.php');

/**
 * Finds requested service through its only method.
 *
 * @static
 */
class ServiceFactory {

    /**
     * @static
     */
    private function __construct() {
        
    }

    /**
     * Returns instance of the requested service.
     *
     * @static
     * @param String $type Name of the service specified in configService.ini file
     * @return ServiceObject
     */
    public static function getService($type) {
        if (!isset($_SESSION)) {
            session_start();
        }

        $type = strtolower($type);
        
        $iniFile = ROOT_PATH . "/backend/config/configService.ini";
        $data = parse_ini_file($iniFile, true);
        $_SESSION['config']['service'] = $data;
       

        $type = strtolower($type);

        // Check that requested Service exists in config
        if (!key_exists($type, $_SESSION['config']['service']['clases'])) {
            throw new ServiceConfigFileException($type, 'The requested service is not configured.', 0);
        }

        if (!file_exists(ROOT_PATH . $_SESSION['config']['service']['includes'][$type])) {
            $message = "The requested service is not properly configured in configService.ini file";
            throw new ServiceConfigFileException($_SESSION['config']['service']['clases'][$type], $message, 0);
        }

        require_once(ROOT_PATH . $_SESSION['config']['service']['includes'][$type]);

        if (!file_exists(ROOT_PATH . $_SESSION['config']['service']['config']['DAOFactory'])) {
            $message = "DAOFactory class is not properly configured in configService.ini file";
            throw new ServiceRequestedClassException('DAOFactory', $message);
        }

        require_once(ROOT_PATH . $_SESSION['config']['service']['config']['DAOFactory']);

        $serviceObject = $_SESSION['config']['service']['clases'][$type];

        if (!class_exists($serviceObject)) {
            $message = "The service is not properly configured in configService.ini file";
            throw new ServiceRequestedClassException($serviceObject, $message); /* MISSING THIRD ARGUMENT */
        }

        return new $serviceObject();
    }

}

?>
