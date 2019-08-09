<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract MysQL Sorguları
 * @version 1.0
 */

class query {
 private $result;
 private $error;
 private $Transactional;
   
 //Sorguyu işliyoruz
 public function __construct ($query, $identifier=null, $isTransactional = true){
  //connection control
  $link = $identifier ? $identifier : CONN;
  //transaction control
  $Transactional = $isTransactional ? $isTransactional : $Transactional;
  if($Transactional){
    //@mysql_query("START TRANSACTION; ",$link); 
  }
	 if (!($this->result = @mysql_query($query, $link))){
	  $this->error = @mysql_error($link);
      //echo ($query." ".$this->error);
      //@mysql_query("ROLLBACK; ",$link); 
	  return false;
	 }else{
	   //@mysql_query("COMMIT; ",$link); 
	  return true;	
	 }
 }

 //Etkilenen satır sayısı
 public function affectedrows (){
	 if (@mysql_affected_rows() > 0){
	  return @mysql_affected_rows();
	  @mysql_free_result($this->result);
	 }else{
	  return -1;	
	 }
 }

 //Eklenen ID
 public function insertid (){
	 if (@mysql_affected_rows() > 0){
	  return @mysql_insert_id();
	  @mysql_free_result($this->result);
	 }else{
	  return -1;	
	 }
 }

 //Toplam satır sayısı
 public function numrows (){
	 if (is_resource($this->result)){
	  return @mysql_num_rows($this->result);
	  @mysql_free_result($this->result);
	 }else{
	  return -1;	
	 }
 }
 
 //Toplam sütun sayısı
 public function numfields (){
	 if (is_resource($this->result)){
	  return @mysql_num_fields($this->result);
	  @mysql_free_result($this->result);
	 }else{
	  return -1;	
	 }
 }

 //Sütun Adı
 public function fieldname ($num){
	 if (is_resource($this->result)){
	  return @mysql_field_name($this->result, $num);
	  @mysql_free_result($this->result);
	 }else{
	  return null;
	 }
 }

 //Nesne olarak çekiyoruz
 public function fetchobject (){
	 if (is_resource($this->result)){
	  return @mysql_fetch_object($this->result);
	  @mysql_free_result($this->result);
	 }else{
	  return null;
	 }
 }

 //Dizi olarak çekiyoruz
 public function fetcharray (){
	 if (is_resource($this->result)){
	  return @mysql_fetch_array($this->result);
	  @mysql_free_result($this->result);
	 }else{
	  return null;
	 }
 }

 //Satır olarak çekiyoruz
 public function fetchrow (){
	 if (is_resource($this->result)){
	  return @mysql_fetch_row($this->result);
	  @mysql_free_result($this->result);
	 }else{
	  return null;
	 }
 }
 
 //Hata
 public function showError (){
  return $this->error;
 }
}
?>