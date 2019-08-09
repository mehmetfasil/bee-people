<?php
/**
 * @author Masters
 * @name Form Elements
 * @access 2016
 * @copyright Masters Corp.
 * @version 1.0
 * @example
 *
 */

/**
 * Form Elements
 *
 * @param string $type
 * @param string $name
 * @param string | array $options
 * @param string | array $value
 * @param string $status
 * @param string $additional
 * @param string $id
 * @return string
 */
function formElement ($type, $name, $options=array(), $value="", $status="", $additional="", $id=""){
 //Eleman
 $element = "";

 //Ek
 $additional = !empty($additional) ? " ".$additional : "";

 //ID farklı yazılmak istenebilir
 $id = empty($id) ? $name : $id;

 //Durum
 $status = !empty($status) ? " ".trim($status)."=\"".trim($status)."\"" : "";

 //Özellikler
 switch ($type){
  case "select":
   //Array var mı karşılaştırıyoruz..
   $options = is_array($options) ? $options : array();

   $element.= "<select".$status." id=\"".$id."\" name=\"".$name."\"".$additional.">\n";

   foreach ($options as $optvalue=>$opttext){
    //Eğer optgroup ise
    if (is_array($opttext) and isset($opttext["label"]) and isset($opttext["options"])) {
     $element.= "<optgroup label=\"".$opttext["label"]."\">\n";
     foreach ($opttext["options"] as $optgvalue=>$optglabel){
      $selected = "";
      //Çoklu seçim varsa
      if (is_array($value)){
       foreach ($value as $tmpvalue){
        if ((string)$optgvalue === (string)$tmpvalue){
         $selected = " selected=\"selected\"";
         break;
        }
       }
      }else{
       if ((string)$optgvalue === (string)$value){
        $selected = " selected=\"selected\"";
       }
      }
      $element.= "<option value=\"".$optgvalue."\"".$selected.">".$optglabel."</option>\n";
     }
     $element.= "</optgroup>\n";
    }else{
     $selected = "";
     //Çoklu seçim varsa
     if (is_array($value)){
      foreach ($value as $tmpvalue){
       if ((string)$optvalue === (string)$tmpvalue){
        $selected = " selected=\"selected\"";
        break;
       }
      }
     }else{
      if ((string)$optvalue === (string)$value){
       $selected = " selected=\"selected\"";
      }
     }
     $element.= "<option value=\"".$optvalue."\"".$selected.">".$opttext."</option>\n";
    }
   }

   $element.= "</select>\n";
   break;
  case "textarea":
   $element.= "<textarea".$status." id=\"".$id."\" name=\"".$name."\" cols=\"5\" rows=\"5\"".$additional.">".stripslashes($value)."</textarea>\n";
   break;
  case "checkbox":
   //Check etme durumu
   $checked = ((string)$options === (string)$value) ? " checked=\"checked\"" : "";
   $element.= "<input".$status." type=\"checkbox\" id=\"".$id."\" name=\"".$name."\" value=\"".stripslashes($options)."\"".$checked.$additional." />\n";
   break;
  case "radio":
   //Check etme durumu
   $checked = ((string)$options === (string)$value) ? " checked=\"checked\"" : "";
   $element.= "<input".$status." type=\"radio\" id=\"".$id."\" name=\"".$name."\" value=\"".stripslashes($options)."\"".$checked.$additional." />\n";
   break;
  default:
   $element.= "<input".$status." class=\"input\" onfocus=\"this.className='inputFocus'\" onBlur=\"this.className='input'\"  type=\"".$type."\" id=\"".$id."\" name=\"".$name."\" value=\"".stripslashes($value)."\"".$additional." />\n";
 }
 return $element;
}

/**
 * Form Buttons
 *
 * @param string $type
 * @param string $name
 * @param string $value
 * @param string $status
 * @param string $icon
 * @param string $additional
 * @param string $id
 * @return string
 */ 
function formButton ($type, $name, $value, $status="", $icon="", $additional="", $id=""){
 //Ek
 $additional = !empty($additional) ? " ".$additional : "";

 //ID farklı yazılmak istenebilir
 $id = empty($id) ? $name : $id;

 //Durum
 $status = !empty($status) ? " ".trim($status)."=\"".trim($status)."\"" : "";

//Buton
 $button = "<button".$status." class=\"formButton\" onmouseover=\"this.className='formButtonHover'\" onmouseout=\"this.className='formButton'\" type=\"".$type."\" id=\"".$id."\" name=\"".$name."\"".$additional.">";
 if (!empty($icon)){
  $button.= "<img src=\"objects/icons/16x16/".$icon."\" alt=\"".$value."\" style=\"padding:0 4px\" />";
 }
 $button.= $value
 . "</button>\n";

 return $button;
}
/**
 * POST, GET, SESSION, SESSION['values'] değişkenlerinden değer alır
 *
 * @param string $key
 * @param mixed $default
 * @param boolean $html_tags
 * @return mixed
 */

function getvalue ($key, $default="", $html_tags=false, $trim=true){
 $text = "";
 
 switch (true){
  case isset($_POST[$key]):
   $text = $_POST[$key];
   break;
  case isset($_GET[$key]):
   $text = urldecode($_GET[$key]);
   break;
  case isset($_SESSION[$key]):
   $text = $_SESSION[$key];
   break;
  case isset($_SESSION["values"][$key]):
   $text = $_SESSION["values"][$key];
   break;
 }
 
 switch (true){
  case is_array($default):
   $text = ((strlen($text) > 0) and in_array($text, $default)) ? $text : current($default);
   break;
  case is_integer($default):
   $text = is_numeric($text) ? (int) $text : $default;
   break;
  case is_float($default):
   $text = is_numeric($text) ? (float) $text : $default;
   break;
  default:
   $text = strlen($text) > 0 ? (string) $text : $default;
   break;
 }
 
 if (!$html_tags) {
 	$text = htmlspecialchars($text);
 }else{
  $text = str_replace("<script", "&lt;sucks", $text);
  $text = str_replace("</script", "&lt;/sucks", $text);
 }
 
 return $trim ? trim($text) : $text;
}
?>