<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Password Generator
 * @version 1.0
 */

class passwordGenerator {
 private $return_type;	 //Tür
 private $group_char;	  //Küçük büyük harfler
 private $groun_num;		  //Rakamlar
 private $return_count; //Kaç karakter olacağı
 private $strmix;       //Karışık

 public function __construct (){
  $this->group_num  = array(0,1,2,3,4,5,6,7,8,9);
  $this->group_char = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
  $this->strmix     = array("a","b","0","c","d","2","e","f","g","1","h","i","j","6","k","l","m","7","n","o","p","5","q","r","s","t","u","v","w","x","y","z","A","B","C","8","D","E","F","G","H","I","J","K","L","9","M","N","O","P","Q","R","4","S","T","U","V","W","X","3","Y","Z");
 }
 
 public function generate ($str_return="mix", $str_count=10){
  $this->return_count = $str_count;

  switch(strtolower($str_return)){
   case "string":
   case "str":
    return $this->getStrVal();
   break;
   case "integer":
   case "int":
    return $this->getIntVal();
   break;
   case "mix":
   case "mixed":
    return $this->getMixVal();
   break;
   default:
    return $this->getStrVal();
   break;
  }
 }
 
 private function getRandNum ($var_count){
  srand ((float)microtime()*1000000);
  return round(rand(0,$var_count));
 }
 
 private function getMixVal (){
  $var_dump = "";
  for ($my_x=0; $my_x < $this->return_count; $my_x++){
   if ($var_dump == ""){
    $dump_data = $this->strmix[$this->getRandNum(count($this->strmix)-1)];
    $var_dump = $var_dump.$dump_data;
   }else{
    $dump_data = $this->strmix[$this->getRandNum(count($this->strmix)-1)];
    $var_dump = $var_dump.$dump_data;
   }
  }
  return substr($var_dump, 0, $this->return_count);
 }
 
 private function getIntVal (){
  $str_dump = "";
  for ($my_x=0; $my_x < $this->return_count; $my_x++){
   if ($str_dump == ""){
    $dump_data = $this->getRandNum(9);
    $str_dump = $dump_data;
   }else{
    $dump_data = $this->getRandNum(9);
    $str_dump = $str_dump.$dump_data;
   }
  }
  return substr($str_dump,0,$this->return_count);
 }
 
 private function getStrVal (){
  $var_dump = "";
  for ($my_x=0; $my_x < $this->return_count; $my_x++){
   if ($var_dump == ""){
    $dump_data = $this->group_char[$this->getRandNum(count($this->group_char)-1)];
    $var_dump = $var_dump.$dump_data;
   }else{
    $dump_data = $this->group_char[$this->getRandNum(count($this->group_char)-1)];
    $var_dump = $var_dump.$dump_data;
   }
  }
  return substr($var_dump, 0, $this->return_count);
 }
}
?>