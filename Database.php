<?php
namespace Model;

class Database {

  	/**
  	 * Creates a databasconnection and returns a PDO object
  	 * @param  DBConfig $config configuration details object
  	 * @return \PDO $conn PDO mysql connection
  	 */
	public function connect(DBConfig $config) {
		try {
			$conn = new \PDO($config->m_host, 
							$config->m_user, 
							$config->m_pass, 
							array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
			$conn->setAttribute( \PDO::ATTR_PERSISTENT, TRUE );
			$conn->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		} catch ( \PDOException $e ) {
			die( 'Connection failed: ' . $e->getMessage() );
		}
		return $conn;
	}
	
	/**
	 * Disconnects to the db.
	 * @param PDO object $conn 
	 */
	public function disconnect($conn) {
		$conn = '';
	}

} 