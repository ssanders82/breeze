<?php
class DatabaseAccess
{
	public static $link;
	static function ConnectDB()
    {
        DatabaseAccess::$link = new PDO('mysql:host=' . MYSQL_LOCATION . ';dbname=' . DATABASE_NAME . ';charset=utf8mb4',
		    MYSQL_USER,
		    MYSQL_PASSWORD,
		    array(
		        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		        PDO::ATTR_PERSISTENT => false
		    )
		);
    }
    
    static function CloseDB()
    {
    	DatabaseAccess::$link = null;
    }
    
    static function Execute($sql, $params = array() )
    {
    	$handle = DatabaseAccess::$link->prepare($sql);
        $handle->execute($params);
        return $handle;
    }
    
    static function Select($sql, $params = array() )
    {
        $handle = DatabaseAccess::Execute($sql, $params);
		$results = $handle->fetchAll(\PDO::FETCH_OBJ);
		return $results;
    }
}
DatabaseAccess::ConnectDB();