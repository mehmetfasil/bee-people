<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Oturum
 * @version 1.0
 */

class session {
 private $session_exists=true; //Oturum var mı?
 private $sessid; //Session ID
 private $userid; //Kullanıcı ID
 private $userip; //Kullanıcı IP
 private $lasttime; //Son zaman
 private $timelimit; // Oturum Süresi (Dk.)
 private $error; // Hata mesajı

 /**
	 * Class
	 *
	 */
 public function __construct ($timelimit=0){
  //Session'a aktarılacak değerler
  if (!isset($_SESSION["SYS_USER_ID"])) {
   //Sonuç doğru döneceğinden verileri session'a aktaralım
   $_SESSION["SYS_USER_ID"]    = 0;
   $_SESSION["SYS_USER_LEVEL"] = 0;
   $this->session_exists = false;
  }

  //Değişkenleri oluşturalım.
  $this->timelimit = is_numeric($timelimit) ? $timelimit : 0;
  $this->sessid    = session_id();
  $this->userid    = $_SESSION["SYS_USER_ID"];
  $this->userip    = isset($_SESSION["SYS_USER_IP"])   ? $_SESSION["SYS_USER_IP"]   : $_SERVER["REMOTE_ADDR"];
  $this->lasttime  = isset($_SESSION["SYS_LAST_TIME"]) ? $_SESSION["SYS_LAST_TIME"] : time();
 }

 /**
	 * Oturum Başladı
	 *
	 */
 public function start (){
  //Oturum kontrolü
  if ($this->session_exists) {
   if (!$this->checkIP()) {
    //Çıkaralım..
    $this->logout();

    //Hata kodu (IP farklı)
    $this->error = "DIFFERENT IP";
    return false;
   }else{
    if (!$this->checkSessionTime()) {
     //Çıkaralım..
     $this->logout();

     //Hata kodu (Süre doldu)
     $this->error = "SESSION TIMED OUT";
     return false;
    }
   }
  }elseif (!$this->startSession()){
   //Çıkaralım..
   $this->logout();

   //Hata kodu (Oluşturulamadı!)
   $this->error = "SESSION START ERROR";
   return false;
  }

  //Hatasız
  $_SESSION["SYS_LAST_TIME"] = time();

  //Online tablosunu güncelledik
  $this->updateSession();
  return true;
 }

 /**
	 * Oturum zaman kontrolü
	 *
	 * @return boolean
	 */
 private function checkSessionTime (){
  //Geçmiş zamanla bize verilen sürenin toplamı şu anki zaman aralığında mı?
  if(!empty($this->timelimit) and (!empty($this->userid)) and (($this->lasttime + ($this->timelimit * 60)) < time())){
   return false;
  }

  //Evetse devam..
  return true;
 }

 /**
  * IP kontrolü
  *
  * @return boolean
  */
 private function checkIP (){
  if ($this->userip == $_SERVER["REMOTE_ADDR"]) {
   //IP'de sorun yok
   return true;
  }

  //Sorun var
  return false;
 }

 /**
	 * Yeni oturum oluşturma
	 *
	 * @return boolean
	 */
 private function startSession (){
  //Etkilenen işlem
  $i=0;

  //Sorgu (ilk kayıt)
  $query = "INSERT INTO `sys_sessions` VALUES ("
  . "'".$this->sessid."', "
  . "NOW(), "
  . "NOW(), "
  . ip2long($this->userip).", "
  . $this->userid.", "
  . (isset($_SERVER["HTTP_REFERER"]) ? "'".$_SERVER["HTTP_REFERER"]."'" : "null").", "
  . (isset($_SERVER["HTTP_USER_AGENT"]) ? "'".$_SERVER["HTTP_USER_AGENT"]."'" : "null")
  . ")";
  $insert = new query($query, CONN);

  if ($insert->affectedrows() > 0) {
   $i++;
  }

  unset($query);
  unset($insert);

  //Online tabosuna ekleyelim
  $query = "INSERT INTO `sys_online` VALUES ("
  . "'".$this->sessid."', "
  . $this->userid.", "
  . "NOW(), "
  . "'".$_SERVER["QUERY_STRING"]."'"
  . ")";
  $insert = new query($query, CONN);

  if ($insert->affectedrows() > 0) {
   $i++;
  }

  //Hatasız mı işlem?
  if ($i == 0) {
   return false;
  }

  //Eklendi
  return true;
 }

 /**
	 * Online tablosunu güncelleştirir
	 *
	 */
 private function updateSession (){
  //Online tabosunu güncelleyelim
  $query = "UPDATE `sys_online` SET "
  . "`oid`=".$this->userid.", "
  . "`time`=NOW(), "
  . "`uri`='".$_SERVER["QUERY_STRING"]."' "
  . "WHERE `sessid`='".$this->sessid."'";
  new query($query, CONN);
 }

 /**
  * Oturum sonlandır
  *
  */
 private function endSession (){
  //Sorgu (Kayıt güncelleme)
  $query = "UPDATE `sys_sessions` SET "
  . "`lasttime`=NOW() "
  . "WHERE `sessid`='".$this->sessid."'";
  new query($query, CONN);
 }

 /**
  * Oturum siler
  *
  */
 private function removeUser (){
  //Sorgu (Kaldırıyoruz)
  $query = "DELETE FROM `sys_online` WHERE `sessid`='".$this->sessid."'";
  new query($query, CONN);
 }

 /**
  * Kalmış oturumları siler
  *
  */
 private function cleanSession (){
  //Öncelikle kalanların son zamanlarını güncelleyelim
  $query = "UPDATE `sys_sessions` AS s "
  . "SET s.`lasttime`=("
  . "SELECT `o`.`time` "
  . "FROM `sys_online` `o` "
  . "WHERE `o`.`sessid`=s.`sessid` "
  . "AND `o`.`time` < DATE_SUB(NOW(), INTERVAL 3 HOUR)"
  . ")";
  new query($query, CONN);

  unset($query);

  //Sonra da silelim
  $query = "DELETE FROM `sys_online` WHERE `time` < DATE_SUB(NOW(), INTERVAL 3 HOUR)";
  new query($query, CONN);
 }


 /**
  * Çıkış
  *
  * @return boolean
  */
 public function logout (){
  //Tabloyu güncelleyelim
  $this->endSession();
  $this->removeUser();
  
  //Eskileri temizleyelim
  $this->cleanSession();

  //Session'daki verileri silelim
  foreach (array_keys($_SESSION) as $key){
   unset($_SESSION[$key]);
  }

  //Session tekrar oluşturuluyor
  session_regenerate_id();
  return true;
 }

 /**
	 * Kuki atar
	 *
	 */
 public function cookie (){
  $users = array();

  if (isset($_COOKIE["WEBIM_COOKIE"])) {
   foreach ($_COOKIE["WEBIM_COOKIE"] as $value){
    $users[$value] = $value;
   }
  }
  

  //Eğer kullanıcı login olmuşsa..
  if ($this->userid > 0) {

   //Yeni oluşturalım..
   setcookie("WEBIM_COOKIE[1]", $this->userid, time()+888888888);

   //Devamı
   $i=2;
   foreach ($users AS $value){
    //Eğer kendisi değilse..
    if ($this->userid <> $value) {
     setcookie("WEBIM_COOKIE[".$i."]", $value, time()+888888888);
     $i++;
    }
   }
  }
 }

 /**
	 * Online kullanıcıları listeler
	 *
	 * @return array
	 */
 public function getOnline (){
  //Sonu
  $result = array();

  //Sorgusu
  $query = "SELECT `u`.`id`, `u`.`name`, `u`.`fullname`, "
  . "DATE_FORMAT(`o`.`time`, '%H:%i:%s') AS `lasttime`, `o`.`uri` "
  . "FROM `sys_online` `o` "
  . "LEFT JOIN `sys_users` `u` ON (`u`.`id`=`o`.`oid`) "
  . "WHERE `o`.`time` > DATE_SUB(NOW(), INTERVAL 1 MINUTE) "
  . "ORDER BY `o`.`time` DESC";
  $select = new query($query, CONN);
  
  while ($row = $select->fetchobject()) {
   $result[$row->id]["username"] = is_null($row->fullname) ? "-" : $row->name;
   $result[$row->id]["fullname"] = is_null($row->fullname) ? label("GUEST") : stripslashes($row->fullname);
   $result[$row->id]["lasttime"] = $row->lasttime;
   $result[$row->id]["lastpage"] = stripslashes($row->uri);
  }

  return $result;
 }

 /**
  * Hata Göster
  *
  * @return string
  */
 public function showError(){
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
