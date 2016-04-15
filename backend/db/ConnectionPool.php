<?php
/* 
 *  To change this template, choose Tools | Templates
 *  and open the template in the editor.
 */

require_once('ADODb.php');

/**
 * Clase que tiene todas las conexiones a la base de datos, de modo de poder
 * conectarse a varias
 *
 * @static
 */
class ConnectionPool {

    /**
     * @staticvar
     * @var Array Array de conexiones a las diferentes Base de Datos
     */
    private static $pool = array();

    /**
     * @static
     */
    private function  __construct() {}

    /**
     * Permite recuperar o construir una conexion a una Base de Datos a traves de su nombre.
     * @static
     * @param String $iniDBFile La ruta relativa al archivo de configuracion configDB.ini
     * @param String $DBName Nombre de la base de datos especificado en configDB.ini
     * @return ADODB El objeto de conexion a la Base de Datos
     */
    public static function getConnection($iniDBFile, $DBName){
        $DBName = strtolower($DBName);
        
        if( !array_key_exists($DBName, self::$pool) ){
            $conn = ADODb::getConnection($iniDBFile, $DBName);
            
            self::addConnection( $conn, $DBName );
            return $conn;
        }
        else{
            return self::$pool[$DBName];
        }
    }

    /**
     * Agrega una conexion al Pool de conexiones.
     * @access Private
     * @static
     * @param ADODB $connectionObject La conexion a la Base de Datos
     * @param String $keyName Nombre de la conexion
     */
    private static function addConnection($connectionObject, $keyName){
    	self::$pool[$keyName] = $connectionObject;
    }
}
?>
