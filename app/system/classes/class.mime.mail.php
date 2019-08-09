<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Mime Mail
 * @version 1.0
 */

class mimeMail	{
 var $parts;
 var $to;
 var $cc;
 var $bcc;
 var $from;
 var $headers;
 var $subject;
 var $body;
 var $html;
 var $host;
 var $port;

 function mimeMail(){
  $this->parts=array();
  $this->to="";
  $this->cc="";
  $this->bcc="";
  $this->from="";
  $this->subject="";
  $this->body="";
  $this->headers="";
  $this->html=false;
 }

 function add_attachment($message, $name="", $ctype="application/octet-stream"){
  $this->parts[]=array(
  "ctype"=>$ctype,
  "message"=>$message,
  "encode"=>"base64",
  "name"=>$name
  );
 }

 function build_message($part){
  $message=$part["message"];
  $message=chunk_split(base64_encode($message));
  $encoding="base64";
  
  return "Content-Type: ".$part["ctype"].";charset=utf-8".($part["name"]? ";name=\"".$part["name"]."\"" : "").
  "\nContent-Transfer-Encoding: $encoding\n\n$message\n";
 }

 function build_multipart(){
  $boundry   = "HKC".md5(uniqid(time()));
  $multipart = "Content-Type: multipart/mixed; boundary= \"$boundry\"\n\n";
  $multipart.= "This is a MIME encoded message.\n\n--$boundry";

  for($i=sizeof($this->parts)-1;$i>=0;$i--){
   $multipart.="\n".$this->build_message($this->parts[$i])."--$boundry";
  }
  return $multipart.="--\n";
 }

 function get_mail($complete=true){
  $mime="";
  if(!empty($this->from)){
   $mime.="From: ".$this->from."\n";
  }
  if(!empty($this->headers)){
   $mime.=$this->headers."\n";
  }
  if($complete){
   if(!empty($this->cc)){
    $mime.="Cc: ".$this->cc."\n";
   }
   if(!empty($this->bcc)){
    $mime.="Bcc: ".$this->bcc."\n";
   }
   if(!empty($this->subject)){
    $mime.="Subject: ".$this->subject."\n";
   }
  }

  if(!empty($this->body)){
   $this->add_attachment($this->body,"",($this->html? "text/html" : "text/plain"));
  }
  $mime.= "MIME-Version: 1.0\n".$this->build_multipart();
  return $mime;
 }

 function send(){
  if(!empty($this->cc)){
   $mime=$this->get_mail(true);
  }else{
   $mime=$this->get_mail(false);
  }

  if(!empty($this->host)){
   ini_set("SMTP", $this->host);
  }

  return @mail($this->to,$this->subject,"",$mime);
 }
}
?>
