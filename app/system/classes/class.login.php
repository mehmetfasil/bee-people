<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Oturum Açma
 * @version 4.0
 */

class login {
 private $user_name;
 private $user_pass;
 private $user_type="name"; //Kullanıcı adı türü
 private $loginTry=5; //Genel giriş denemesi
 private $userTry=10; //Kullanıcı giriş denemesi
 private $userLocked=false; //Kilitli mi?
 private $emailConfirmed=false;
 private $error; // Hata mesajı

 public function __construct ($user_name, $user_pass, $hashed=false, $user_type="name", $loginTry=10, $userTry=5){
  $this->user_name  = trim(strip_tags($user_name));
  $this->user_pass  = $hashed ? $user_pass : md5($user_pass);
  $this->user_type  = $user_type;
  $this->loginTry   = $loginTry;
  $this->userTry    = $userTry;
 }

 public function doLogin () {
  if (empty($_SESSION["SYS_USER_ID"])){
   if ($this->checkLoginTry()){
    if (!empty($this->user_name)){
     if ($this->checkName($this->user_type)){
      if ($this->checkUserTry()){
       if ($this->checkLogin()){
        if ($this->userLocked){
         //Hata kodu (Kullanıcı adı kilitli)
         $this->error = "USER ACCOUNT IS LOCKED";
         return false;
        }else{
         if($this->emailConfirmed==false){
           $this->error = "USER NOT CONFIRM EMAIL";
           return false; 
         }else{
            return true;
         }
        }
       }else{
        //Hata kodu (Kullanıcı şifre hatası)
        $this->error = "USER NAME AND PASSWORD DOES NOT MATCH";
        return false;
       }
      }else{
       //Hata kodu (Kullanıcı denemesi (hesap kilitlendi!))
       $this->error = "TOO MANY TRY, ACCOUNT IS LOCKED";
       return false;
      }
     }else{
      //Hata kodu (Kullanıcı adı uyuşmadı)
      $this->error = "INVALID USER NAME PHRASE";
      return false;
     }
    }else{
     //Hata kodu (Kullanıcı adı yok)
     $this->error = "USER NAME MUST ENTERED";
     return false;
    }
   }else{
    //Hata kodu (Giriş denemesi)
    $this->error = "TOO MANY TRY";
    return false;
   }
  }else{
   //Hata kodu (Zaten giriş yapmış)
   $this->error = "ALREADY LOGGED IN";
   return false;
  }
 }

 private function checkLoginTry (){
  //Genel deneme sayısı, kullanıcı denemesi farklı..
  //Deneme sayısını artırıyoruz
  $_SESSION["SYS_LOGIN_TRY"] = (empty($_SESSION["SYS_LOGIN_TRY"])) ? 1 : $_SESSION["SYS_LOGIN_TRY"] + 1;

  if ($_SESSION["SYS_LOGIN_TRY"] > $this->loginTry){
   //Deneme sayısını aştı..
   //Hata
   return false;
  }

  //Sonuç
  return true;
 }

 private function checkUserTry (){
  //Bu kullanıcı adı kaç kere denendi?
  $_SESSION["SYS_USER_TRY_".$this->user_name] = empty($_SESSION["SYS_USER_TRY_".$this->user_name]) ? 1 : ($_SESSION["SYS_USER_TRY_".$this->user_name] + 1);

  if ($_SESSION["SYS_USER_TRY_".$this->user_name] > $this->userTry){
   //Bu şekilde bir kullanıcı adı var mı kontrol ediyoruz..
   $query = "SELECT u.`id` "
   . "FROM `sys_users` AS u "
   . "INNER JOIN `sys_objects` AS o ON (u.`id`=o.`id`) "
   . "WHERE u.`name`='".$this->user_name."' "
   . "AND o.`status`='1'";
   $select = new query($query, CONN);

   if ($select->numrows() > 0){
    //Bu deneme sayısını 0'lıyoruz..
    unset($_SESSION["SYS_USER_TRY_".$this->user_name]);

    //MySQL'den kaydı değiştiriyoruz
    $query = "UPDATE `sys_users` SET "
    . "`lock`='1' "
    . "WHERE `name`='".$this->user_name."'";
    new query($query, CONN);

    //Hesap kilitlendi..
    //Hata
    return false;
   }
  }
  //Sonuç
  return true;
 }

 private function checkName ($type="name"){
  switch ($type){
   case "email":
    $pattern = "/^[\w.]+@([\w.]+)\.[a-z]{2,6}$/i";
    break;
   default:
    $pattern = "/^[a-zA-Z0-9_\(\@\.\)\[\]]{3,30}$/";
  }

  if (!preg_match($pattern, $this->user_name)){
   //Verilen karakterlere uymadı..
   //Hata
   return false;
  }

  //Sonuç
  return true;
 }

 private function checkLogin (){
  //Sorgusu
  $query = "SELECT u.`id`, u.`name`, u.`fullname`, "
  . "u.`detail`, u.`email`, u.`cpnl`, "
  . "IF(`pass_expiration` < NOW(), '1', '0') AS expired,"
  . "u.`level`, u.`lock` "
  . "FROM `sys_users` AS u "
  . "INNER JOIN `sys_objects` AS o ON (u.`id`=o.`id`) "
  . "WHERE u.`name`='".$this->user_name."' "
  . "AND u.`pass`='".$this->user_pass."' "
  . "AND o.`active`='1'";
  $select = new query($query, CONN);

  if ($select->numrows() > 0){
   //Kullanıcı adı ve şifre uyuştuğuna göre
   while ($row = $select->fetchobject()){
    //eger kullanıcı mailini kontrol etmemiş ise
    if($row->detail=="email_sended"){
     $this->emailConfirmed = false;
     return true;
    }else{
     $this->emailConfirmed = true;
    }
    //Eğer kullanıcı kilitli ise
    if ($row->lock == "1"){
     //Kilitlendi mesajı
     $this->userLocked = true;

     //Kullanıcının hesabı kilitlenmiş..
     //Hata
     return true;
    }else{
     //Session'a değerler aktarılıyor
     $_SESSION["SYS_USER_ID"]       = $row->id; // Kullanıcı ID
     $_SESSION["SYS_USER_NAME"]     = $row->name; // Kullanıcı Adı
     $_SESSION["SYS_USER_FULLNAME"] = $row->fullname; // Kullanıcı İsmi
     $_SESSION["SYS_USER_DETAIL"]   = stripslashes($row->detail); // Kullanıcı Açıklaması
     $_SESSION["SYS_USER_EMAIL"]    = $row->email; // E-Posta Adresi
     $_SESSION["SYS_USER_LEVEL"]    = $row->level; // Kullanıcı Yetkisi
     $_SESSION["SYS_USER_CPNL"]     = $row->cpnl; // İlk girişte şifresini değiştirsin mi?
     $_SESSION["SYS_USER_EXPIRED"]  = $row->expired; // Parolanın süresi doldu mu?

     //Deneme sayılarını sıfırlıyoruz..
     unset($_SESSION["SYS_LOGIN_TRY"]);
     unset($_SESSION["SYS_USER_TRY_".$this->user_name]);

     //Session kaydını güncelleyelim..
     //Oturum süresi güncelleniyor
     new query("UPDATE `sys_sessions` SET `oid`=".$_SESSION["SYS_USER_ID"]." WHERE `sessid`='".session_id()."'", CONN);
     new query("UPDATE `sys_online` SET `oid`=".$_SESSION["SYS_USER_ID"]." WHERE `sessid`='".session_id()."'", CONN);

     //Sonuç
     return true;
    }
   }
  }
  //Kullanıcı adı, şifre uyuşmazlığı var..
  //Hata
  return false;
 }

 public function showError (){
  return $this->error;
 }
}
?>