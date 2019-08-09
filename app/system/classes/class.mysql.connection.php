<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract MysQL Bağlantısı
 * @version 1.0
 */

class myConnect {
	private $host;
	private $user;
	private $pass;
	private $database;
 private $error;
	
 /**
  * Class
  *
  * @param string $host
  * @param string $user
  * @param string $pass
  * @param string $database
  * @param numeric $port
  */
	public function __construct ($host, $user, $pass, $database, $port=3306){
		$this->host = $host.":".$port;
		$this->user = $user;
		$this->pass = $pass;
		$this->database = $database;
	}
	
	/**
	 * Bağlantı
	 *
	 * @return object
	 */
	public function connect (){
		$identifier = @mysql_connect($this->host, $this->user, $this->pass);
		
		if ($identifier) {
			$connection = @mysql_select_db($this->database, $identifier);
			
			if ($connection) {
				//@mysql_query("SET NAMES LATIN5", $identifier);
				return $identifier;
			}else{
				$this->error = sprintf("DATABASE ERROR {%s}", @mysql_error());
				return false;
			}
		}else{
			$this->error = sprintf("CONNECTION ERROR {%s}", @mysql_error());
			return false;
		}
	}
	
	/**
	 * Bağlantıyı bitirir
	 *
	 * @param object $connection
	 */
	public function disconnect ($connection=null){
		@mysql_close($connection);
	}

	/**
	 * Hata Göster
	 *
	 * @return string
	 */
 public function showError (){
		return $this->error;
 }
 
 /**
  * Class'ı bellekten siler
  *
  */
 public function __destruct (){
 	//Bitirir
 }
}
?>
