<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Yetkili Sayfalar
 * @version 1.0
 */

class page {
 private $userid=0;
 private $userlevel=0;
 private $docroot=CONF_DOCUMENT_ROOT;

 /**
  * Constructor
  *
  * @param integer $userid
  * @param integer $userlevel
  * @param string $docroot
  */
 public function __construct ($userid=0, $userlevel=0, $docroot=""){
 	//header("Content-type: text/html; charset=utf-8");	// "oksis.pa.edu.tr icin ozel eklendi" - Snowblind
  $this->userid    = $userid;
  $this->userlevel = $userlevel;
  $this->docroot   = $docroot;
 }

 /**
  * Show Page
  *
  * @param integer $mid
  * @param string $sid
  */
 public function showPage ($mid=0, $sid="index", $prefix=""){
  //Gelen alt sayfa sorgular mı?
  if ($sid == "query") {
   //Madem sayfa sorgu sayfası gönderen alt sayfa ne?
   if (isset($_SERVER["HTTP_REFERER"]) || (config("CONF_SYSTEM_DEBUG_MODE", 0) == 1)) {
    //$referer = $_SERVER["HTTP_REFERER"];
    $referer = ((isset($_SERVER["HTTP_REFERER"]))?($_SERVER["HTTP_REFERER"]):(""));
    $referer_sid = "index";

    //Gelen refererdan önceki sid'i çekiyoruz
    if (preg_match("/sid=(\w+)/", $referer, $match)) {
     $referer_sid = $match[1];
    }

    //Sayfayı alalım
    $page = $this->getPage($mid, $referer_sid);

    //Sayfa var mı?
    if ($page) {
     //Sayfanın türü ne?
     if (($page["type"] == "site") || isset($_GET[ADMIN_EXTENSION])) {
      //Yetki seviyesi yetiyor mu?
      if ($this->userlevel >= $page["level"]){
       //Yetkileri alalım
       $permissions = $this->getPermissions($page["pid"]);

       //Yetkileri karşılaştıralım
       if (($page["level"] == 0) || ($permissions{0} == 1)){
        if (($permissions{1} == 0) && ((ACT == "save") || (STEP == "save") || (OPER == "save"))){
         //Yazma yetkisi yok!
         $this->getQueryPage($page["pid"], $this->showText("YOU HAVE NO PERMISSION TO WRITE"));
        }elseif (($permissions{2} == 0) && ((ACT == "delete") || (STEP == "delete") || (OPER == "delete"))){
         //Silme yetkisi yok!
         $this->getQueryPage($page["pid"], $this->showText("YOU HAVE NO PERMISSION TO DELETE"));
        }else{
         //Yetki tanımları
         define("ISWRITABLE", ($permissions{1} == 1 ? true : false));
         define("ISDELETABLE", ($permissions{2} == 1 ? true : false));
         define("ISUPLOADABLE", ($permissions{3} == 1 ? true : false));

         $this->getQueryPage($page["pid"]);
        }
       }else{
        $this->showErrorPage($this->showText("YOU HAVE NO PERMISSION TO VIEW THIS PAGE"), $this->showText("YOU HAVE NO PERMISSION TO VIEW THIS PAGE"), $page["template"]);
       }
      }else{
       $this->showErrorPage($this->showText("INSUFFICIENT PRIVILEGES"), $this->showText("INSUFFICIENT PRIVILEGES"), $page["template"]);
      }
     }else{
      $this->showErrorPage($this->showText("PAGE NOT FOUND"), $this->showText("PAGE NOT FOUND"), $page["template"]);
     }
    }else{
     $this->showErrorPage($this->showText("PAGE NOT FOUND"), $this->showText("PAGE NOT FOUND"), $page["template"]);
    }
   }else{
    //Direk yazılmış olduğundan hata döndürelim?
    $this->showErrorPage($this->showText("REFERER DOES NOT EXIST"), $this->showText("REFERER DOES NOT EXIST"));
   }
  }else{
   header("Content-type: text/html; charset=utf-8");	// "oksis.pa.edu.tr icin ozel eklendi" - Snowblind
   //Sayfayı alalım
   $page = $this->getPage($mid, $sid);

   //Sayfa var mı?
   if ($page) {
    //Sayfanın türü ne?
    if (($page["type"] == "site") || isset($_GET[ADMIN_EXTENSION])) {
     //Yetki seviyesi yetiyor mu?
     if ($this->userlevel >= $page["level"]){
      //Yetkileri alalım
      $permissions = $this->getPermissions($page["pid"]);

      if (($page["level"] == 0) || ($permissions{0} == 1)) {
       //Sayfa site mi yönetim mi?
       if (!isset($_GET[ADMIN_EXTENSION]) && ($page["type"] =="admin")) {
        $this->showErrorPage($this->showText("YOU CANNOT VIEW THIS PAGE"), $this->showText("YOU CANNOT VIEW THIS PAGE"), $page["template"]);
       }else{
        //Dosya Başlığı
        define("WEBIM_PAGE_CAPTION", $page["caption"]);
        // Dosya Şablonu 
        define("WEBIM_PAGE_TEMPLATE", $page["template"]);
        switch ($page["source"]){
         case "file":

          //Dosya
          $file = $this->getFileName($page["pid"]);

          if ($file) {
           //Yetki tanımları
           define("ISWRITABLE", ($permissions{1} == 1 ? true : false));
           define("ISDELETABLE", ($permissions{2} == 1 ? true : false));
           define("ISUPLOADABLE", ($permissions{3} == 1 ? true : false));

           $content = $this->getFileContent($file);
           
           if ($content){
            //Başlık
            $title = (strlen($prefix) > 0 ? trim($prefix)." " : "").$page["title"];
            $this->createHTMLPage($title, $content, $page["template"]);
           }else{
            $this->showErrorPage($this->showText("HTML CONTENT NOT FOUND"), $this->showText("HTML CONTENT NOT FOUND"), $page["template"]);
           }
          }else{
           $this->showErrorPage($this->showText("FILE NOT FOUND"), $this->showText("FILE NOT FOUND"), $page["template"]);
          }

          break;
         case "html":

          $content = $this->getHTML($page["pid"]);
          
          if ($content) {
           //Başlık
           $title = (strlen($prefix) > 0 ? trim($prefix)." " : "").$page["title"];
           $this->createHTMLPage($title, $content, $page["template"]);
          }else{
           $this->showErrorPage($this->showText("HTML CONTENT NOT FOUND"), $this->showText("HTML CONTENT NOT FOUND"), $page["template"]);
          }

          break;
        }
       }
      }else{
       $this->showErrorPage($this->showText("YOU HAVE NO PERMISSION TO VIEW THIS PAGE"), $this->showText("YOU HAVE NO PERMISSION TO VIEW THIS PAGE"), $page["template"]);
      }
     }else{
        header("Location:index.php");
      $this->showErrorPage($this->showText("INSUFFICIENT PRIVILEGES"), $this->showText("INSUFFICIENT PRIVILEGES"), $page["template"]);
     }
    }else{
     $this->showErrorPage($this->showText("PAGE NOT FOUND"), $this->showText("PAGE NOT FOUND"), $page["template"]);
    }
   }else{
    $this->showErrorPage($this->showText("PAGE NOT FOUND"), $this->showText("PAGE NOT FOUND"));
   }
  }
 }

 /**
  * Show Page Directly From File
  *
  * @param string $path
  * @param string $template
  */
 public function showPageUsingFile ($path, $title="Web-IM", $template="_default"){
  //Dosya
  $file = $this->docroot.$path;

  //Varsa
  if (file_exists($file)) {
   $this->createHTMLPage($title, $this->getFileContent($file), $template);
  }else{
   $this->showErrorPage($this->showText("FILE NOT FOUND"), $this->showText("FILE NOT FOUND"), $template);
  }
 }
 
 /**
  * Get Page Content
  *
  * @param integer $mid
  * @param string $sid
  * @return string
  */
 private function getPageContent ($mid=0, $sid="index"){
  //Dönecek sonuç
  $content = "";
  
  if ($sid != "query") {
   //Sayfayı alalım
   $page = $this->getPage($mid, $sid);
   
   if ($page){
    //Sayfanın türü ne?
    if (($page["type"] == "site") || isset($_GET[ADMIN_EXTENSION])) {
     //Yetki seviyesi yetiyor mu?
     if ($this->userlevel >= $page["level"]){
      //Yetkileri alalım
      $permissions = $this->getPermissions($page["pid"]);
 
      if (($page["level"] == 0) || ($permissions{0} == 1)) {
       //Sayfa site mi yönetim mi?
       if (!isset($_GET[ADMIN_EXTENSION]) && ($page["type"] =="admin")) {
        $content = $this->showText("YOU CANNOT VIEW THIS PAGE");
       }else{
        switch ($page["source"]){
         case "file":
 
          //Dosya
          $file = $this->getFileName($page["pid"]);
 
          if ($file) {
           $content = $this->getFileContent($file);
 
           if (!$content){
            $content = $this->showText("HTML CONTENT NOT FOUND");
           }
          }else{
           $content = $this->showText("FILE NOT FOUND");
          }
 
          break;
         case "html":
 
          $content = $this->getHTML($page["pid"]);
 
          if (!$content) {
           $content = $this->showText("HTML CONTENT NOT FOUND");
          }
 
          break;
        }
       }
      }else{
       $content = $this->showText("YOU HAVE NO PERMISSION TO VIEW THIS PAGE");
      }
     }else{
      $content = $this->showText("INSUFFICIENT PRIVILEGES");
     }
    }else{
     $content = $this->showText("PAGE NOT FOUND");
    }
   }else{
    $content = $this->showText("PAGE NOT FOUND");
   }
  }

  return $content;
 }

 /**
  * Get File Contents
  *
  * @param string $path
  * @return boolean, string
  */
 private function getFileContent ($path){
  if (is_file($path)) {
   ob_start();
   include_once($path);
   $content = ob_get_contents();
   ob_end_clean();
   return (strlen($content) > 0 ? $content : " ");
  }
  return false;
 }

 /**
  * Get Page Information From DB
  *
  * @param integer $mid
  * @param string $sid
  * @return boolean, array
  */
 private function getPage ($mid=0, $sid="index"){
  //ID 0'dan büyük mü?
  if ($mid > 0) {
   //Sorgusu
   $query = "SELECT `p`.`id`, `m`.`type`, `p`.`template`, `p`.`title`, "
   . "`p`.`caption`, `p`.`icon`, `p`.`position`, `p`.`source`, `m`.`level` "
   . "FROM `sys_menus` `m` "
   . "INNER JOIN `sys_pages` p ON (`m`.`id`=`p`.`mid` AND `p`.`source`!='none') "
   . "WHERE `m`.`active`='1' "
   . "AND `m`.`id`=".$mid." "
   . "AND `p`.`sid`='".$sid."'";
   $select = new query($query, CONN);

   if ($select->numrows() > 0) {
    $row = $select->fetchobject();

    return array(
    "pid"      => $row->id,
    "type"     => $row->type,
    "template" => ($row->position=="relative" ? $row->template : null),
    "title"    => $this->showText(stripslashes($row->title)),
    "caption"  => $this->showText(stripslashes($row->caption)),
    "icon"     => $row->icon,
    "position" => $row->position,
    "source"   => $row->source,
    "level"    => $row->level
    );
   }
  }

  return false;
 }

 /**
  * Get The Membered Group IDs
  *
  * @param integer $oid
  * @return array
  */
 private function getObjectIDs ($oid){
  $gids = array();

  if ($oid > 0) {
   //Sorgusu
   $query = "SELECT `gid` "
   . "FROM `sys_group_members` "
   . "WHERE `member`=".$oid;
   $select = new query($query, CONN);
   while ($row = $select->fetchobject()) {
    $gids[$row->gid] = $row->gid;
    $gids += $this->getObjectIDs($row->gid);
   }
  }

  return $gids;
 }

 /**
	 * Get Page Permissions
	 *
	 * @param integer $pid
	 * @return boolean, string
	 */
 private function getPermissions ($pid=0){
  $groupPerms = $this->getGroupPermissions($pid);
  $userPerms  = $this->getUserPermissions($pid);

  if ($userPerms["r"] > -1) {
   if ($userPerms["r"] == 1) {
    return (string) "1".($userPerms["w"] < 1 ? 0 : 1).($userPerms["d"] < 1 ? 0 : 1).($userPerms["u"] < 1 ? 0 : 1);
   }elseif ($groupPerms["r"] > 0){
    return (string) "1".($groupPerms["w"] < 1 ? 0 : 1).($groupPerms["d"] < 1 ? 0 : 1).($groupPerms["u"] < 1 ? 0 : 1);
   }
  }
  return (string) "0000";
 }

 /**
  * Get User Permissions
  *
  * @param integer $pid
  * @return array
  */
 private function getUserPermissions ($pid=0){
  $result = array("r"=>0, "w"=>0, "d"=>0, "u"=>0);

  if ($pid > 0) {
   //Sorgusu
   $query = "SELECT `permit`, `restrict` "
   . "FROM `sys_permissions` "
   . "WHERE `pid`=".$pid." "
   . "AND `oid`=".$this->userid;
   $select = new query($query, CONN);

   if ($select->numrows() > 0) {
    $row = $select->fetchobject();

    $permit = (string) str_pad($row->permit, 4, "0");;
    $restrict = (string) str_pad($row->restrict, 4, "0");

    //Sonuç
    $result["r"] = (int) ($permit{0}-$restrict{0});
    $result["w"] = (int) ($permit{1}-$restrict{1});
    $result["d"] = (int) ($permit{2}-$restrict{2});
    $result["u"] = (int) ($permit{3}-$restrict{3});
   }
  }

  return $result;
 }

 /**
  * Get Group Permissions
  *
  * @param integer $pid
  * @return array
  */
 private function getGroupPermissions ($pid=0){
  $result = array("r"=>0, "w"=>0, "d"=>0, "u"=>0);

  if ($pid > 0) {
   //Grup ID'ler
   $gids = $this->getObjectIDs($this->userid);

   if (count($gids) > 0) {
    //Sorgusu
    $query = "SELECT `permit`, `restrict` "
    . "FROM `sys_permissions` "
    . "WHERE `pid`=".$pid." "
    . "AND `oid` IN (".implode(",", $gids).") "
    . "ORDER BY `oid`";
    $select = new query($query, CONN);
    while ($row = $select->fetchobject()) {
     $permit = (string) str_pad($row->permit, 4, "0");;
     $restrict = (string) str_pad($row->restrict, 4, "0");

     //Sonuç
     $result["r"] += (int) ($permit{0}-$restrict{0});
     $result["w"] += (int) ($permit{1}-$restrict{1});
     $result["d"] += (int) ($permit{2}-$restrict{2});
     $result["u"] += (int) ($permit{3}-$restrict{3});
    }
   }
  }

  return $result;
 }

 /**
  * Creates and Shows Query Page
  *
  * @param integer $pid
  * @param string $content
  */
 private function getQueryPage ($pid=0, $content=null){
  $tags = (defined("RESULTAS") && (RESULTAS == "text")) ? false : true;
  if ($tags){
   //XML Header
   ob_start();	// snowblind
   header("Content-type: text/xml; charset=utf-8");

   //XML
   echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
   echo "<content type=\"query\" createdBy=\"system\" creationDate=\"".date("Y-m-d H:i:s")."\">";
  }

  if ($pid > 0) {
   $file = $this->getFileName($pid);
   if ($file) {
    $dirname = dirname($file);
    $queryfile = $dirname.DS."query.php";

    if (file_exists($queryfile)) {
     if ($content) {
      printf("%s".$content."%s", ($tags ? "<result status=\"ERROR\"><![CDATA[" : ""), ($tags ? "]]></result>" : ""));
     }else{
      include_once($queryfile);
     }
    }else{
     printf("%s".$this->showText("FILE NOT FOUND")."%s", ($tags ? "<result status=\"ERROR\"><![CDATA[" : ""), ($tags ? "]]></result>" : ""));
    }
   }else{
    printf("%s".$this->showText("FILE NOT FOUND")."%s", ($tags ? "<result status=\"ERROR\"><![CDATA[" : ""), ($tags ? "]]></result>" : ""));
   }
  }else{
   printf("%s".$this->showText("PAGE NOT FOUND")."%s", ($tags ? "<result status=\"ERROR\"><![CDATA[" : ""), ($tags ? "]]></result>" : ""));
  }

  if ($tags){
   echo "</content>";
   ob_end_flush();	// snowblind
  }
 }

 /**
  * Get Page File
  *
  * @param integer $pid
  * @return boolean, string
  */
 private function getFileName ($pid){
  //Sorgusu
  $query = "SELECT `file` "
  . "FROM `sys_page_file` "
  . "WHERE `pid`=".$pid;
  $select = new query($query, CONN);

  if ($select->numrows() > 0) {
   $row = $select->fetchobject();

   if (file_exists($this->docroot.$row->file)) {
    return $this->docroot.$row->file;
   }
  }

  return false;
 }

 /**
	 * Get Page HTML Content
	 *
	 * @param integer $pid
	 * @return boolean, string
	 */
 private function getHTML ($pid){
  //Sorgusu
  $query = "SELECT `text`, `title` "
  . "FROM `sys_content` "
  . "JOIN `sys_pages` ON (`sys_pages`.`id`=`sys_content`.`id`) "
  . "WHERE `sys_content`.`type`='PAGE' "
  . "AND `sys_content`.`language`='".LANG."' "
  . "AND `sys_content`.`id`=".$pid;
  $select = new query($query, CONN);

  if ($select->numrows() > 0) {
   $row = $select->fetchobject();

   return openTab(array(stripslashes($row->title)),$row->title)."<div style='padding:15px; background:url(templates/_default/images/pa.jpg)  no-repeat; line-height:20px;'>".stripslashes($row->text)."</div>".closeTab();
  }

  return false;
 }

 /**
	 * Get Page HTML Header
	 *
	 * @param string $template
	 * @see 27/04/2009 22:07 degisiklik yapıldı
	 */
 private function createHTMLPage ($title, $content, $template=null){
  if ($template) {
   define("WEBIM_PAGE_TITLE", $title);
   define("WEBIM_PAGE_CONTENT", $content);
   //Şablon dosyası
   $template_file = $this->docroot."templates".DS.$template.DS."index.php";
   if (file_exists($template_file)) {
   	// eger daha once tanımlanmamışsa
    !defined("WEBIM_PAGE_TEMPLATE") ? define("WEBIM_PAGE_TEMPLATE", $template) : "";
    include_once($template_file);
   }else{
    echo $this->createRawHTML($title, $content);
   }
  }else{
   echo $content;
  }
 }

 /**
	 * Creates HTML Header and Footer
	 *
	 * @param string $title
	 * @param string $content
	 * @return string
	 */
 public static function createRawHTML ($title, $content){ 
  
  $html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\r\n"
  . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"".LANG."\" lang=\"".LANG."\">\r\n"
  . "<head>\r\n"
  . "<title>".$title."</title>\r\n"
  . "<meta http-equiv=\"Content-type\" content=\"text/html; charset=".LANGUAGE_CHARSET."\" />\r\n"
  . "<style type=\"text/css\">\r\n"
  . "<!--\r\n"
  . "html { margin:0; height:auto; height:100%!important; }\r\n"
  . "body { margin:10px 0; font:12px Arial }\r\n"
  . "div { font-size:12px }\r\n"
  . ".error { text-align:center; }\r\n"
  . ".error div { width:80%; margin:10px auto; background-color:#ffecec; border:2px solid #8a1b1b; color:#510909; padding:5px; font-size:24px }\r\n"
  . "-->\r\n"
  . "</style>\r\n"
  . "</head>\r\n"
  . "<body>\r\n"
  . $content
  . "</body>\r\n"
  . "</html>";
  return $html;
 }

 /**
	 * Any Error Page
	 *
	 * @param string $errorText
	 * @param string $template
	 */
 public static function showErrorPage ($errotTitle, $errorText, $template="_default"){
  $title = "Web-IM ".label("ERROR")." - ".$errotTitle;
  $content = "<div class=\"error\"><div>"
  . $errorText
  . "</div></div>\r\n";

  if ($this && $template) {
   $this->createHTMLPage($title, $content, $template);
  }else{
   echo page::createRawHTML($title, $content);
  }
 }

 /**
	 * Language Text Translator
	 *
	 * @param string $definition
	 * @param string $language
	 * @return string
	 */
 public static function showText ($definition, $language="default"){
  //Sonuç
  $language = ($language == "default") ? LANG : $language;
  $languageText = page::getLangFileVarsIntoArray($language);
  $text = $definition;

  $matches = array();
  if (preg_match_all("/\{(.*?)\}/", $text, $out)) {
   foreach ($out[0] as $replaceText){
    $text = trim(str_replace($replaceText, "", $text));
   }
   $matches = $out[1];
  }

  if (isset($languageText[$text])) {
   if (count($matches) > 0) {
    $str = implode("\", \"", $matches);
    eval('$text = @sprintf($languageText[$text], "'.$str.'");');
   }else{
    $text = $languageText[$text];
   }
  }

  return $text;
 }

 /**
  * Gets The Language File and Converts Into Array
  *
  * @param string $lang
  */
 public static function getLangFileVarsIntoArray ($language){
  $vars = array();
  $consts = get_defined_constants(true);
  $user_consts = $consts["user"];
  $const = "LANGUAGE_TEXTS_".strtoupper($language);

  if (isset($user_consts[$const])) {
   //Bir kere çekeceğiz
   $vars += unserialize($user_consts[$const]);
  }else{
   //Dosya
   $filename = CONF_DOCUMENT_ROOT."system".DS."languages".DS.$language.DS."language";

   //Yoksa
   if (!file_exists($filename)){
    $filename = CONF_DOCUMENT_ROOT."system".DS."languages".DS.DEFAULT_LANGUAGE.DS."language";
   }

   $handle = @fopen($filename, "r");

   if ($handle) {
    while (!feof($handle)) {
     $row = preg_replace("/[\r\n]+/", "", fgets($handle, 4096));

     if (preg_match("/(.*)(\:\=)(.*)/", $row)){
      $raw = explode(":=", $row);
      $vars[trim(@$raw[0])] = trim(@$raw[1]);
     }
    }
    @fclose($handle);
   }

   define($const, serialize($vars));
  }

  return $vars;
 }

 /**
  * Destructor
  *
  */
 public function __destruct(){
  //Bitirir
 }
}
?>