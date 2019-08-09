<?php
defined("PASS") or die("Dosya yok!");

//Resim yolu
$path = "objects/assets/products/".getvalue("path")."/";

switch (ACT) {
 case "list":
  
  
  
  break;
	case "save":
		
	 if (getvalue("kategori",0) > 0) {
	 	if (strlen(getvalue("baslik")) > 0) {
	 	 $search_pattern = array("@\.@","@\,@");
	 	 $replace_pattern = array("",".");
	 	 
	 	 $alisfiyat = number_format(preg_replace($search_pattern, $replace_pattern, getvalue("alisfiyat")), 2, ".", "");
	 	 $satisfiyat = number_format(preg_replace($search_pattern, $replace_pattern, getvalue("satisfiyat")), 2, ".", "");
	 	 $indirim = number_format(preg_replace($search_pattern, $replace_pattern, getvalue("indirim")), 2, ".", "");
	 	 
	 	 if ($satisfiyat > 0) {
	 	 	if (getvalue("id",0) > 0) {
	 	 		//Update
	 	 		$query = "UPDATE sepet SET "
	 	 		. "dil='', "
	 	 		. "kategori=".getvalue("kategori",0).", "
	 	 		. "WHERE id=".getvalue("id",0);
	 	 	}else{
	 	 	 //Insert
	 	 	}
	 	 }else{
  	  echo "<result status=\"ERROR\"><![CDATA[".label("PRODUCT SELLING PRICE IS EMPTY")."]]></result>";
	 	 }
	 	}else{
 	  echo "<result status=\"ERROR\"><![CDATA[".label("PRODUCT TITLE EMPTY")."]]></result>";
	 	}
	 }else{
	  echo "<result status=\"ERROR\"><![CDATA[".label("PRODUCT CATEGORY MUST BE SELECTED")."]]></result>";
	 }
	 
		break;
	case "values":
		
	 
	 
		break;
	case "delete":
		
	 
	 
		break;
	case "categories":
	 
		$tmp = array();

		//Sorgusu
		$query = "SELECT `id`, `parent`, `baslik` "
		. "FROM `sepet_kategoriler` "
		. "WHERE `aktif`='1' "
		. "ORDER BY `parent`, `sira`";
		$select = new query($query, CONN);
		while ($row = $select->fetchobject()) {
			$tmp[$row->parent][$row->id] = label(stripslashes($row->baslik));
		}

		//Array
		$result = sortList($tmp, current(array_keys($tmp)), 0);

		//Sonuç
		foreach ($result as $id=>$value){
			echo "<list id=\"".$id."\" level=\"".$value["level"]."\">";
			echo "<![CDATA[".$value["title"]."]]>";
			echo "</list>";
		}
	 
	 break;
	case "pictures":
	 
  //Resimler
  $images = getFiles($path, "", array(".png"));
  
  foreach ($images as $image){
   echo "<list><![CDATA[".$path.$image."]]></list>";
  }
	 
	 break;
 case "delpic":
  
  if (strlen(getvalue("picture")) > 0) {
  	if (file_exists(CONF_DOCUMENT_ROOT.getvalue("picture"))) {
  	 if (unlink(CONF_DOCUMENT_ROOT.getvalue("picture"))){
   	 echo "<result status=\"OK\" />";
  	 }else{
   	 echo "<result status=\"ERROR\"><![CDATA[".label("AN ERROR OCCURED WHILE DELETING")."]]></result>";
  	 }
  	}else{
  	 echo "<result status=\"ERROR\"><![CDATA[".label("FILE NOT EXISTS")."]]></result>";
  	}
  }else{
 	 echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
  }
  
  break;
}
?>