<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Genel Fonksiyonlar
 * @version 1.0
 */

defined("PASS") or die("Dosya yok!");

/**
 * Sets Menu List
 *
 */
function setMenuIDs (){
 $query = "SELECT `id`, `name` "
 . "FROM `sys_menus` "
 . "WHERE `active`='1'";
 $select = new query($query, CONN);
 while ($row = $select->fetchobject()) {
  if (!defined($row->name)) {
  	define($row->name, $row->id);
  }
 }
}

/**
 * Sets Configuration
 *
 * @param instance $conn
 *
 */
function setConfiguration ($conn=CONN){
 //Ayarlar
 $query = "SELECT CONCAT(`module`,'_',`variable`) AS `variable`, `value` "
 . "FROM `sys_configuration`";
 $select = new query($query, $conn);
 while ($row = $select->fetchrow()) {
  if (!defined($row[0])) {
  	define($row[0], stripslashes($row[1]));
  }
 }
}

/**
 * Gets Configuration
 *
 * @param string $var
 * @param string $default
 * @return string
 */
function config ($var, $default=null){
	$consts = get_defined_constants(true);
	$user_consts = $consts["user"];
	return (key_exists($var, $user_consts) ? $user_consts[$var] : $default);
}

/**
 * Sets current language properties
 *
 */
function setLanguageProperties (){
 $filename = CONF_DOCUMENT_ROOT."system".DS."languages".DS.LANG.DS."language";
 
 if (!file_exists($filename)) {
  $filename = CONF_DOCUMENT_ROOT."system".DS."languages".DS.DEFAULT_LANGUAGE.DS."language"; 	
 }
 $handle = @fopen($filename, "r");
 
 if ($handle) { 
  $row = fgets($handle, 4096);
  if (preg_match("/\[(.*?)\]/", $row, $match)){
   $all = explode(";", $match[1]);
   foreach ($all as $exp){
    if (strlen(trim($exp)) > 0){
     $raw = explode("=", $exp);
     if (isset($raw[1]) and !defined("LANGUAGE_".trim($raw[0]))) {
     	define("LANGUAGE_".trim($raw[0]), trim($raw[1]));
     }
    }
   }
  }
  @fclose($handle);
 }
}

/**
 * @abstract Chekcs UTF-8 character groups
 * @param string $string
 * @return boolean
 */
function isUTF8 ($string){
 return preg_match("%(?:
 [\xC2-\xDF][\x80-\xBF]        					# non-overlong 2-byte
 |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
 |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
 |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
 |\xF0[\x90-\xBF][\x80-\xBF]{2}    	# planes 1-3
 |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
 |\xF4[\x80-\x8F][\x80-\xBF]{2}    	# plane 16
 )+%xs", $string);
}

/**
 * @abstract Validate E-Mail Address
 * @param string $subject
 * @return boolean
 */
function isEmail ($subject){
 $pattern = "/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
 
 if (preg_match($pattern, $subject)) {
 	return true;
 }
 return false;
}

/**
 * @abstract System Folders
 * @return array
 */
function getFolders ($path, $first="", $sort="asc"){
	$result = array();

	//Diziye ekleyelim
	if (!empty($first)){
		array_push($result, $first);
	}

	//Yol
	$path = ($path{strlen($path)-1} == DS) ? $path : $path.DS;
	$path = (strpos($path, CONF_DOCUMENT_ROOT) === false) ? CONF_DOCUMENT_ROOT.$path : $path;

	if (!file_exists($path)){
		@mkdir($path, 0755);
	}

	$handle = @opendir($path);
	while (false !== ($folder = @readdir($handle))) {
		if (($folder != ".") and ($folder != "..") and is_dir($path.$folder)){
			$result[$folder] = $folder;
		}
	}
	@closedir($handle);

	//Sıralama
	if ($sort == "asc") {
		asort($result);
	}else{
		krsort($result);
	}
	reset($result);

	return $result;
}

/**
 * @abstract System Files
 * @return array
 */
function getFiles ($path, $first="", $extensions=array(), $ext_in_notin="in", $sort="asc"){
	$result = array();

	//Diziye ekleyelim
	if (!empty($first)){
		array_push($result, $first);
	}

	//Yol
	$path = ($path{strlen($path)-1} == DS) ? $path : $path.DS;
	$path = (strpos($path, CONF_DOCUMENT_ROOT) === false) ? CONF_DOCUMENT_ROOT.$path : $path;

	if (!file_exists($path)){
		@mkdir($path, 0755);
	}

	$handle = @opendir($path);
	while (false !== ($file = @readdir($handle))) {
		if (($file != ".") and ($file != "..") and is_file($path.$file)){
			if (is_array($extensions) and (count($extensions) > 0)) {
				//Dosyanın uzantısı
				$ext = strtolower(strrchr($file, "."));

				if ($ext_in_notin == "in") {
					//Listede varsa..
					if (in_array($ext, $extensions)) {
						$result[$file] = $file;
					}
				}else{
					//Listede yoksa..
					if (!in_array($ext, $extensions)) {
						$result[$file] = $file;
					}

				}
			}else{
				$result[$file] = $file;
			}
		}
	}
	@closedir($handle);

	//Sıralama
	if ($sort == "asc") {
		asort($result);
	}else{
		krsort($result);
	}
	reset($result);

	return $result;
}

/**
 * @abstract Number Counting
 * @return array
 */
function countNumbers ($start, $finish, $first=array(), $pad=array(), $format=""){
	$first = is_array($first) ? $first : array();
	$padding = array("length"=>1,"str"=>0,"type"=>"left");
	
	if (is_array($pad)) {
		foreach ($pad as $key=>$value){
		 $padding[$key] = $value;
		}
	}
	
	$options = array();
	
	if ((int) $start > (int) $finish){
	 $x = (int) $finish;
	 $y = (int) $start;
	}else{
	 $x = (int) $start;
	 $y = (int) $finish;
	}
	
	for ($x; $x <= $y; $x++){
	 $text = str_pad((string) $x, $padding["length"], (string) $padding["str"], ($padding["type"]=="right" ? STR_PAD_RIGHT : STR_PAD_LEFT));
	 $options[$x] = (strlen($format) > 0 ? sprintf($format, $text) : $text);
	}
	
	if ($start > $finish){
	 $options = array_reverse($options, true);
	}
	
	return ($first + $options);
}

/**
 * @abstract Get List From MySQL
 * @return array
 */
function getList ($table, $fields="*", $clause="", $order="", $additional="", $handle=CONN){
	//Sonuç
	$result = array();

	//Şart cümlesi
	$clause = trim(str_replace("WHERE", "", $clause));

	if (!empty($additional)){
		array_push($result, $additional);
	}

	//Sorgu
	$query = "SELECT ".$fields." FROM ".$table;
	$query.= strlen($clause) > 0 ? " WHERE ".$clause : "";
	if (!empty($order)){
		$query.= " ORDER BY ".$order;
	}
	$select = new query($query, $handle);
	while ($row = $select->fetchrow()){
		$result[$row[0]] = stripslashes($row[1]);
	}
	return $result;
}

/**
 * @abstract Shows One Value From MySQL
 * @return string
 */
function showValue ($table, $field="*", $clause="", $handle=CONN){
	//Sonuç
	$result = "";

	//Şart cümlesi
	$clause = trim(str_replace("WHERE", "", $clause));

	//Sorgu
	$query = "SELECT ".$field." FROM ".$table;
	$query.= strlen($clause) > 0 ? " WHERE ".$clause : "";
	$select = new query($query, $handle);
	while ($row = $select->fetchrow()){
		$result = stripslashes($row[0]);
	}
	return $result;
}

/**
 * @abstract Clears the beginning of a url
 * @return string
 */
function clearURL ($url, $text){
	//Gelen URL adresi
	$url = str_replace("/", "\/", $url);
	$url = str_replace(".", "\.", $url);

	//Text
	$text = stripslashes($text);

	//Arama kriterlerimiz
	$pattern  = "/(src|href|url)\=([\"'`])(".$url."[\"'`]?)/";

	//Gelen metni düzeltiyoruz..
	$text = preg_replace($pattern, "\\1=\\2", $text);

	return $text;
}

/**
 * @abstract Menu ID
 * @return integer
 */
function menuID ($menu_name){
	$array = get_defined_constants();

	if (key_exists($menu_name, $array)) {
		$result = $array[$menu_name];
	}else{
		$result = 0;
	}

	return $result;
}

/**
 * Gelen string tarih mi?
 *
 * @param string $str 14-09-2016
 * @return boolen
 */
function isDate ($str){
 $raw = explode("/", $str);
 if (count($raw) == 3) {
  $date = mktime(0,0,0,$raw[1],$raw[0],$raw[2]);
  if ((date("d",$date)==$raw[0]) and (date("m",$date)==$raw[1]) and (date("Y",$date)==$raw[2])){
   return true;
  }
 }
 return false;
}

/**
 * Date insert to mysql
 * @param string $str 13/08/2016
 * @return $str
 * */
 function convertToMysqlDateFormat($str){
    if(!isDate($str))
    return "";
    $e = explode("/",$str);
    return $e[2]."-".$e[1]."-".$e[0];
 }
 
 
function isHourValid($str){
    return (preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9])/", $str));
}

/**
 * trim masked input
 * @param string $str (554) 681-9955
 * @return string
 * 
 **/
function trimMaskedDataForPhone($str){
    $str = str_replace("_","",str_replace("-","",str_replace(")","",str_replace("(","",str_replace(" ","",$str)))));
    return $str;
}

/**
 * check tl currency
 * @param float $str (1.000,03)
 * @return string
 * */
function checkTLCurrency($str, $virgul=3, $ondalik='.', $tam='') {
    return number_format($str, $virgul, $ondalik, $tam);
}

/**
 * finalize date format for DB save
 * @param date $date (21/02/2016)
 * @return @string
 **/
function finalizeDateForDb($date){
    if($date!="" && $date!="NULL"){
        $date = "'".$date."'";
    }else{
        //date is set as null.
        $date = "NULL";
    }
    return $date;
}

/**
 * lower str according to turkish characters 
 * @param string $str MEHMETFASİL@GMAİL.COM
 * @return string
 **/
 
function strtolowerTR($str){ 
    $arananlar=array('/I/','/İ/','/Ş/','/Ö/','/Ü/','/Ğ/','/Ç/'); 
    $yeniler=array('ı','i','ş','ö','ü','ğ','ç'); 
    ksort($arananlar); 
    ksort($yeniler); 
    $str=preg_replace($arananlar, $yeniler, $str); 
    $str=strtolower($str); 
    return $str; 
}  


/**
 * Bugünden önceki tarih mi?
 *
 * @param string $date 21-09-2016
 * @return boolen
 */
function isBeforeNow ($date){
 $now = strtotime("now");
 $raw = split("[/.-]", $date);
 $tmp = $raw[2].$raw[1].$raw[0];

 if (date("Ymd", $now) >= $tmp){
  return true;
 }

 return false;
}

/**
 * Tarih verir
 *
 * @param string $return
 * @return string
 */
function showDate ($return="date"){
	$months = array(
	 1 => "JANUARY",
	 "FEBRUARY",
	 "MARCH",
	 "APRIL",
	 "MAY",
	 "JUNE",
	 "JULY",
	 "AUGUST",
	 "SEPTEMBER",
	 "OCTOBER",
	 "NOVEMBER",
	 "DECEMBER"
	);
	
	$days = array(
	 1 => "MONDAY",
	 "TUESDAY",
	 "WEDNESDAY",
	 "THURSDAY",
	 "FRIDAY",
	 "SATURDAY",
	 "SUNDAY"
	);
	
	$days_abbr = array(
	 1 => "MON",
	 "TUE",
	 "WED",
	 "THU",
	 "FRI",
	 "SAT",
	 "SUN"
	);

	switch ($return) {
		case "months":			
			return $months;
			break;
		case "days":
			return $days;
			break;
		case "days_abbr":
			return $days_abbr;
			break;
		default:
			return date("d")." ".label($months[date("n")])." ".date("Y").", ".label($days[(date("w") == 0 ? 7 : date("w"))]);
	}
}

/**
 * @abstract Crop Text
 * @return string
 */
function cropText ($text, $max=30){
	$text = strip_tags(stripslashes($text));
	$text = str_replace("\n", "", $text);

	if (strlen($text) > $max){
		preg_match("/.*\s/", substr($text, 0, $max), $found);
		$text = substr("{$found[0]}", 0, -1)." ...";
	}

	return $text;
}

/**
 * Array'i XML'e çevirir
 *
 * @param array $info
 */
function showArrayToXML ($info){
 if (count($info) > 0) {
  foreach ($info as $bilgi=>$bilgiler){
   if (isset($bilgiler["label"])){
    echo "<info label=\"".$bilgiler["label"]."\">";
    unset($bilgiler["label"]);

    if (isset($bilgiler["head"]) and isset($bilgiler["body"])){
     echo "<head>";
     foreach ($bilgiler["head"] as $value){
      echo "<title".(isset($value["width"]) ? " width=\"".$value["width"]."\"" : "");
      echo (isset($value["align"]) ? " align=\"".$value["align"]."\"" : "").">";
      echo "<![CDATA[".$value["title"]."]]>";
      echo "</title>";
     }
     echo "</head>";
     echo "<body>";
     foreach ($bilgiler["body"] as $rows){
      echo "<row>";
      foreach ($rows as $columns){
       echo "<column><![CDATA[".$columns."]]></column>";
      }
      echo "</row>";
     }
     echo "</body>";
    }elseif (isset($bilgiler["images"])){
     echo "<images>";
     foreach ($bilgiler["images"] as $value){
      echo "<image>";
      echo "<id><![CDATA[".$value["id"]."]]></id>";
      echo "<description><![CDATA[".$value["description"]."]]></description>";
      echo "</image>";
     }
     echo "</images>";
    }elseif (is_array($bilgiler)){
     foreach ($bilgiler as $value){
      echo "<value label=\"".$value["label"]."\"><![CDATA[".$value["value"]."]]></value>";
     }
    }
    echo "</info>";
   }
  }
 }
}

/**
 * Diziyi liste olarak düzenler
 *
 * @param array $array
 * @param integer $parent
 * @param integer $i
 * @return array
 */
function sortList ($array, $parent, $i=0){
	$result = array();
	foreach ($array as $parentid=>$nextarray){
		if ($parentid == $parent){
			foreach ($nextarray as $key=>$value){				
				$result[$key]["level"]  = $i;
				$result[$key]["parent"] = $parent;
				$result[$key]["title"]  = $value;

				if (key_exists($key, $array)){
					$result += sortList($array, $key, ($i+1));
				}
			}
		}
	}
	$i++;
	return $result;
}


/**
 * Language List
 *
 * @return array
 */
function getLanguages (){
 $languages = array();
 $lang_folder = CONF_DOCUMENT_ROOT."system".DS."languages".DS;
 
 $folders = getFolders($lang_folder);
 
 foreach ($folders as $folder){ 	
 	if(strpos($folder,".")!==false) continue;
  $handle = @fopen($lang_folder.$folder.DS."language", "r");
  
  if ($handle) {
   $row = fgets($handle, 4096);
   
   if (preg_match("/\[(.*?)\]/", $row)){
    preg_match("/ABBR=(.*?)[;]/", $row, $match1);
    preg_match("/NAME=(.*?)[;]/", $row, $match2);
    $abbr = @$match1[1];
    $name = @$match2[1];
    $languages[$abbr] = $name;
   }else{
    $languages[label("LANGUAGE_ABBR")] = label("LANGUAGE_NAME");
   }
   @fclose($handle);
  }
 }
 return $languages;
}

/**
 * shortcut for label
 * */

/**
 * shortcut for page::showText
 * */
function label($text="Write a statement"){
	return page::showText($text);
}
/**
 * gets the most upper site for given page id 
 * @param Integer pid
 * @param String Array sites
 * @return String
 * */

function getPageSite($pid=0,$sites=array()){
	if($pid!=0){

		$name = trim(showValue("sys_menus","name","id=".$pid));
		if(in_array($name,$sites)){
			return $name;
		}else {
			return getTopParent((int)showValue("sys_menus","parent","id=".$pid),$sites);
		}
		
	}
}
/**
*  Open Block
*/
function openBlock($title=""){
	?>
	<div class="blockTop">
			<div class="blockTopLeft"></div>
			<div style="padding-top:12px;"><b><?=$title?></b></div>
		</div>
		<div style="display:block; border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
		<div style="width:100%; clear:both; display:inline-table" >
<?
}

function closeBlock ($content=""){
	?>
		</div>
		</div>
		<div class="tabMenuBottom">
			<div class="tabMenuBottomLeft"></div>
			<div style="padding:12px 10px 4px 10px "><?=$content?></div>
		</div>
<?	
}


function openTab($tabs=array(),$selected_tab_key=""){
	
	$tab="<div class=\"tabMenu\" style=\"width:100%\">".
		"<ul>";
			
			if(count($tabs)){
				foreach ($tabs as $key=>$value){
					
					$tab.="<li";
					($value!=$selected_tab_key) ? $tab.=" class=current": "" ; 
					$tab.=">";
					$tab.="<span>".$value."</span>";
					$tab.="</li>";
				}
			}
		
		$tab.="</ul>";
		$tab.="<div class=\"tabMenuRight\"></div>";
	$tab.="</div>";
	$tab.="<div style=\"display:block; border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC;\">";
		$tab.="<div style=\" width:100%; clear:both; display:inline-block\" >";
	return $tab;
}


function closeTab($content=""){
	
		$tab="</div>".
		"</div>".
		"<div class=\"tabMenuBottom\" style=\"clear:both;\">".
			"<div class=\"tabMenuBottomLeft\"></div>".
			"<div style=\"padding:12px 10px 4px 10px; \">".$content."</div>".
		"</div>";
		return $tab;
}

function is_utf8 ($string){
 return preg_match('%(?:
 [\xC2-\xDF][\x80-\xBF]        					# non-overlong 2-byte
 |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
 |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
 |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
 |\xF0[\x90-\xBF][\x80-\xBF]{2}    	# planes 1-3
 |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
 |\xF4[\x80-\x8F][\x80-\xBF]{2}    	# plane 16
 )+%xs', $string);
}
?>