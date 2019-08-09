<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Ağaç Yapısı
 * @version 1.0
 */

class xmlTree {
 private $array = array();

 public function __construct($array){
  if (is_array($array)) {
   $this->array = $array;
  }
 }

 public function createXML ($id){
  $result = "";
  if (isset($this->array[$id])) {
   $result.= "<fulllist total=\"".count($this->array)."\">"
   . "<list id=\"".$id."\" parent=\"".$this->array[$id]["parent"]."\">";
   foreach ($this->array[$id] as $k=>$v){
    if ($k != "parent"){
     $result.= "<".$k."><![CDATA[".$v."]]></".$k.">";
    }
   }
   $result.= $this->setSubs($id)
   . "</list>"
   . "</fulllist>";
  }
  return $result;
 }

 private function getSubs ($id){
  $result = array();
  foreach ($this->array as $key=>$values){
   if (isset($values["parent"]) and ($values["parent"] == $id)) {
    foreach ($values as $k=>$v){
     $result[$key][$k] = $v;
    }
   }
  }
  return $result;
 }

 private function setSubs ($id){
  $result = "";
  $subpages = $this->getSubs($id);
  if (count($subpages) > 0) {
   $result.= "<sub>";
   foreach ($subpages as $key=>$values){
    $result.= "<list id=\"".$key."\" parent=\"".$values["parent"]."\">";
    foreach ($values as $k=>$v){
     if ($k != "parent"){
      $result.= "<".$k."><![CDATA[".$v."]]></".$k.">";
     }
    }
    $result.= $this->setSubs($key)
    . "</list>"; 
   }
   $result.= "</sub>";
  }
  return $result;
 }
}
?>