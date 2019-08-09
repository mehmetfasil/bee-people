<?php
defined("PASS") or die("Dosya yok!");

switch (ACT){
 case "ssh":
  
  //SSH Bağlantı dosyası
  require_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.ssh.php");
  
  $host = "127.0.0.1";
  $port = 22;
  $user = "user";
  $password = "pass";

  try {
  	$ssh = new SSH_in_PHP($host,$port);
  	$ssh->connect($user,$password);
  
  	$cycle = true;
  	while ($cycle) {
  		$data = $ssh->read();
  		echo $data;
  		if (ereg('\$',$data)) {
  			$cycle = false;
  		}
  	}
  	$ssh->write("uname -a\n");
  	$cycle = true;
  	while ($cycle) {
  		$data = $ssh->read();
  		echo $data;
  		if (ereg('\$',$data)) {
  			$cycle = false;
  		}
  	}
  	
  	$ssh->write("ls -al\n");
  	$cycle = true;
  	while ($cycle) {
  		$data = $ssh->read();
  		echo $data;
  		if (ereg('\$',$data)) {
  			$cycle = false;
  		}
  	}
  
  	$ssh->disconnect();
  
  } catch (SSHException $e) {
  	echo "An Exception Occured: {$e->getMessage()} ({$e->getCode()})\n";
  	echo "Trace: \n";
  	echo print_r($e->getTrace());
  	echo "\n";
  }
  
  break;
}
?>