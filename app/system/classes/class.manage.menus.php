<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Manage Menu
 * @version 1.0
 */

class manageMenu {
 private $vars = array(
  "id"=>0,
  "parent"=>0,
  "type"=>"site",
  "name"=>"SITE_",
  "icon"=>"spacer.gif",
  "caption"=>"",
  "detail"=>null,
  "link"=>null,
  "target"=>"_self",
  "order"=>1,
  "split"=>0,
  "level"=>0,
  "visibility"=>"visible",
  "active"=>1,
  "subpages"=>array()
 );
	private $error;

	public function __construct ($vars=array()){
		//Başlattık..
		if (is_array($vars) and count($vars) > 0) {
 		$this->vars = $vars;
		}
	}

	public function setMenuId ($value){
		$this->vars["id"] = is_numeric($value) ? $value : 0;
	}

	public function setMenuParent ($value){
		$this->vars["parent"] = is_numeric($value) ? $value : 0;
	}

	public function setMenuType ($value){
	 $array = array("site","admin");
		$this->vars["type"] = in_array($value, $array) ? $value : current($array);
	}

	public function setMenuName ($value){
		$this->vars["name"] = preg_replace("/[^a-zA-Z0-9_]/", "", $value);
	}

	public function setMenuIcon ($value){
		$this->vars["icon"] = $value;
	}

	public function setMenuCaption ($value){
		$this->vars["caption"] = $value;
	}

	public function setMenuDetail ($value){
		$this->vars["detail"] = $value;
	}

	public function setMenuLink ($value){
		if (preg_match("/pid=0/", $value)){
			//0 yazılıysa gelecek olanla değiştiriyoruz..
			$this->vars["link"] = preg_replace("/pid=0/", "pid=".$this->pickMenuID(), $value);
		}else{
			//Normal gelen
			$this->vars["link"] = $value;
		}
	}

	public function setMenuTarget ($value){
	 $array = array("_self","_blank");
		$this->vars["target"] = in_array($value, $array) ? $value : current($array);
	}

	public function setMenuOrder ($value){
		$this->vars["order"] = is_numeric($value) ? $value : 0;
	}

	public function setMenuSplit ($value){
	 $array = array(0,1);
		$this->vars["split"] = in_array($value, $array) ? $value : current($array);
	}

	public function setMenuLevel ($value){
	 $array = array(0,1,2,3);
		$this->vars["level"] = in_array($value, $array) ? $value : current($array);
	}

	public function setMenuVisibility ($value){
	 $array = array("visible","hidden");
		$this->vars["visibility"] = in_array($value, $array) ? $value : current($array);
	}

	public function setMenuActive ($value){
	 $array = array(0,1);
		$this->vars["active"] = in_array($value, $array) ? $value : current($array);
	}

	public function setSubPages ($value){
	 if (is_array($value) and (count($value) > 0)) {
	 	$this->vars["subpages"] = $value;
	 }
	}

	public function checkMenuID (){
		//Boşsa veya 0 sa..
		if (empty($this->vars["id"])) {
			return false;
		}

		//ID kontrolü..
		$query = "SELECT `id` "
		. "FROM `sys_menus` "
		. "WHERE `id`=".$this->vars["id"];
		$select = new query($query, CONN);

		if($select->numrows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function pickMenuID (){
		//En sonuncusunu çekelim
		$query = "SELECT MAX(`id`) + 1 AS `id` "
		. "FROM `sys_menus`";
		$select = new query($query, CONN);
		$row = $select->fetchobject();

		return $row->id;
	}

	public function pickMenuOrder ($parent){
		//Listenin en sonuncunu getirelim
		$query = "SELECT (MAX(`order`) + 1) AS `order` "
		. "FROM `sys_menus` "
		. "WHERE `visibility`='visible' "
		. "AND `parent`=".$parent;
		$select = new query($query, CONN);
		$row = $select->fetchobject();

		return $row->order;
	}

	public function checkMenuName (){
		//ID kontrolü..
		$query = "SELECT `id` "
		. "FROM `sys_menus` "
		. "WHERE `name`='".$this->vars["name"]."'";
		$select = new query($query, CONN);

		if($select->numrows() > 0){
			return false;
		}else{
			return true;
		}
	}
	
	private function getSubPages (){
	 $result = array();
	 
	 //Sorgu
	 $query = "SELECT `p`.`id`, `f`.`pid` AS `file`, `c`.`id` AS `html` "
	 . "FROM `sys_pages` `p` "
	 . "LEFT JOIN `sys_page_file` `f` ON (`p`.`id`=`f`.`pid`) "
	 . "LEFT JOIN `sys_content` `c` ON (`p`.`id`=`c`.`id` AND `c`.`type`='PAGE') "
	 . "WHERE `p`.`mid`=".$this->vars["id"];
	 $select = new query($query, CONN);
	 
	 while ($row = $select->fetchobject()) {
	 	$result[$row->id]["file"] = $row->file;
	 	$result[$row->id]["html"] = $row->html;
	 }
	 
	 return $result;
	}
	
	private function addSubPages (){
	 $added = 0;
	 $subpages = $this->getSubPages();
	 
	 if (count($this->vars["subpages"]) > 0) {
 	 foreach ($this->vars["subpages"] as $values){
 	  $id = 0;
 	  if (isset($subpages[$values["id"]])) {
  	  //Dosyayı silelim
 	  	new query("DELETE FROM `sys_page_file` WHERE `pid`=".$values["id"]);
  	  
 	  	//İçeriği silelim
 	  	if ($values["source"]!="html") {
 	  		new query("DELETE FROM `sys_content` WHERE `id`=".$values["id"]." AND `type`='PAGE'");
 	  	}
 	  	
 	  	//Update
 	  	$query = "UPDATE `sys_pages` SET ";
 	  	if ($subpages[$values["id"]]["sid"] != "index") {
 	  		$query.= "`sid`='".$values["sid"]."', ";
 	  	}
 	  	$query.= "`template`=".($values["position"]=="absolute" ? "null" : "'".$values["template"]."'").", "
 	  	. "`title`='".mysql_escape_string($values["title"]=="INDEX" ? $this->vars["caption"] : $values["title"])."', "
 	  	. "`caption`='".mysql_escape_string($values["caption"]=="INDEX" ? $this->vars["caption"] : $values["caption"])."', "
 	  	. "`icon`='".$values["icon"]."', "
 	  	. "`position`='".$values["position"]."', "
 	  	. "`visibility`='".$values["visibility"]."', "
 	  	. "`source`='".$values["source"]."' "
 	  	. "WHERE `id`=".$values["id"];
 	  	
 	  	$save = new query($query, CONN);
 	  	
 	  	if ($save->affectedrows() > 0) {
 	  		$added++;
 	  	}
 	  	
 	  	$id = $values["id"];
 	  	
 	  	//Listeden çıkaralım, kalanları sileceğiz
 	  	unset($subpages[$values["id"]]);
 	  }else{
 	   //Insert
   	 $query = "INSERT INTO `sys_pages` VALUES ("
   	 . "null, "
   	 . $this->vars["id"].", "
   	 . "'".$values["sid"]."', "
   	 . ($values["position"]=="absolute" ? "null" : "'".$values["template"]."'").", "
   	 . "'".mysql_escape_string($values["title"]=="INDEX" ? $this->vars["caption"] : $values["title"])."', "
   	 . "'".mysql_escape_string($values["caption"]=="INDEX" ? $this->vars["caption"] : $values["caption"])."', "
   	 . "'".$values["icon"]."', "
   	 . "'".$values["position"]."', "
   	 . "'".$values["visibility"]."', "
   	 . "'".$values["source"]."'"
   	 . ")";
   	 $save = new query($query, CONN);
 	  	
 	  	if ($save->affectedrows() > 0) {
 	  	 $id = $save->insertid();
 	  		$added++;
 	  	}
 	  }
 	  
 	  //Kaynağını ekleyelim
 	  if ($id > 0) {
  	  if ($values["source"] == "file") {
  	  	//Ekleyelim
  	  	$query = "INSERT INTO `sys_page_file` VALUES ("
  	  	. $id.", "
  	  	. "'".preg_replace("/^admin\//", "", $values["path"])."'"
  	  	. ")";
  	  	$save = new query($query, CONN);
  	  	
  	  	if ($save->affectedrows() > 0) {
  	  		$added++;
  	  	}
  	  }else if ($values["source"] == "html"){
  	  	//Ekleyelim
  	  	$query = "INSERT INTO `sys_content` VALUES ("
  	  	. $id.", "
  	  	. "'PAGE', "
  	  	. "'".DEFAULT_LANGUAGE."', "
  	  	. "'<div class=\"construction\">".label("UNDER CONSTRUCTION")."</div>', "
  	  	. "NOW(), "
  	  	. $_SESSION["SYS_USER_ID"].", "
  	  	. "null, "
  	  	. "null, "
  	  	. "0, "
  	  	. "0"
  	  	. ")";
  	  	$save = new query($query, CONN);
  	  	
  	  	if ($save->affectedrows() > 0) {
  	  		$added++;
  	  	}
  	  }
 	  }
 	 }
 	 
 	 //Kalanlar (silinmiş demektir)
 	 $ids = array_keys($subpages);
 	 
 	 if (count($ids) > 0) {
  	 $this->clearWaste($ids);
 	 }
	 }else{
 	 //Varolanları silelim
 	 $ids = array_keys($subpages);
 	 
 	 if (count($ids) > 0) {
  	 $this->clearWaste($ids);
 	 }
 	 
	  if ($this->addSubPage()){
	   $added++;
	  }
	 }
	 
	 return $added;
	}
	
	private function addSubPage (){
	 //Sorgu
	 $query = "INSERT INTO `sys_pages` VALUES ("
	 . "null, "
	 . $this->vars["id"].", "
	 . "'index', "
	 . "'".config("CONF_SITE_TEMPLATE", "_default")."', "
	 . "'".mysql_escape_string($this->vars["caption"])."', "
	 . "'".mysql_escape_string($this->vars["caption"])."', "
	 . "'spacer.gif', "
	 . "'relative', "
	 . "'visible', "
	 . "'none'"
	 . ")";
	 $save = new query($query, CONN);
	 
	 if ($save->affectedrows() > 0) {
	 	return true;
	 }else{
	  return false;
	 }
	}

	public function save (){
		//Varsa güncelleme yoksa ekleme
		if ($this->checkMenuID()){
			if ($this->vars["id"] == 0) {
				$this->vars["id"] = $this->pickMenuID();
			}
			if (strlen($this->vars["caption"]) > 0){
				//Sıralama
				$this->setOrders($this->vars["parent"], $this->vars["order"], $this->vars["id"]);

				//Update
				$query = "UPDATE `sys_menus` SET "
				. "`parent`=".$this->vars["parent"].", "
				. "`type`='".$this->vars["type"]."', "
				. "`icon`='".$this->vars["icon"]."', "
				. "`caption`='".$this->vars["caption"]."', "
				. "`detail`='".$this->vars["detail"]."', "
				. "`link`=".(strlen($this->vars["link"]) > 0 ? "'".$this->vars["link"]."'" : "null").", "
				. "`target`='".$this->vars["target"]."', "
				. "`order`=".$this->vars["order"].", "
				. "`split`='".$this->vars["split"]."', "
				. "`level`=".$this->vars["level"].", "
				. "`visibility`='".$this->vars["visibility"]."', "
				. "`active`='".$this->vars["active"]."' "
				. "WHERE `id`=".$this->vars["id"];
				$save = new query($query, CONN);

				if (($save->affectedrows() > 0) or ($this->addSubPages() > 0)) {
 				return true;
				}else{
				 $this->error = "MADE NO UPDATE";
				 return false;
				}
			}else{
				$this->error = "MENU CAPTION MUST BE FILLED";
				return false;
			}
		}else{
			if (strlen($this->vars["caption"]) > 0){
				if ($this->checkMenuName()){
					//Sıralama
					//Sıralama verilmemişse en sonuncusunu seçip ekliyoruz..
					$this->order = empty($this->vars["order"]) ? $this->pickMenuOrder($this->vars["parent"]) : $this->vars["order"];

					//Sıralayalım..
					$this->setOrders($this->vars["parent"], $this->vars["order"], $this->vars["id"]);

					//Insert
					$query = "INSERT INTO `sys_menus` "
					. "VALUES ("
					. $this->vars["id"].", "
					. $this->vars["parent"].", "
					. "'".$this->vars["type"]."', "
					. "'".$this->vars["name"]."', "
					. "'".$this->vars["icon"]."', "
					. "'".$this->vars["caption"]."', "
					. "'".$this->vars["detail"]."', "
					. (strlen($this->vars["link"]) > 0 ? "'".$this->vars["link"]."'" : "null").", "
					. "'".$this->vars["target"]."', "
					. $this->vars["order"].", "
					. "'".$this->vars["split"]."', "
					. $this->vars["level"].", "
					. "'".$this->vars["visibility"]."', "
					. "'".$this->vars["active"]."'"
					. ")";
					$insert = new query($query, CONN);
					echo mysql_error();
					//Sonuç
					if ($insert->affectedrows() > 0){
					 if ($this->addSubPages() > 0) {
					 	return true;
					 }else{
					  $this->error = "SUBPAGE NOT ADDED";
						 return false;
					 }
					}else{
						$this->error = "NOT SAVED";
						return false;
					}
				}else{
					$this->error = "MENU EXISTS WITH SAME NAME {".$this->vars["name"]."}";
					return false;
				}
			}else{
				$this->error = "MENU CAPTION MUST BE FILLED";
				return false;
			}
		}
	}

	public function delete (){
		//Sıralama için parent'ı çekelim ve varlığını da kontrol etmiş olalım
		$query = "SELECT `parent` FROM `sys_menus` WHERE `id`=".$this->vars["id"];
		$select = new query($query, CONN);

		if ($select->numrows() > 0) {
			$row = $select->fetchobject();
			$parent = $row->parent;

			//Kontrol edelim alt menüsü var mı?
			$query = "SELECT `id` FROM `sys_menus` WHERE `parent`=".$this->vars["id"];
			$select = new query($query, CONN);

			if ($select->numrows() > 0) {
				$this->error = "THIS MENU IS PARENT OF ANOTHER MENU";
				return false;
			}else{
				//ID'ler
				$ids = array();

				//Önce sayfaları silelim
				$query = "SELECT `id` "
				. "FROM `sys_pages` "
				. "WHERE `mid`=".$this->vars["id"];
				$select = new query($query, CONN);
				while ($row = $select->fetchobject()) {
					$ids[$row->id] = $row->id;
				}

				if (count($ids) > 0){
				 $this->clearWaste($ids);
				}

				//Menüyü silelim
				$query = "DELETE FROM `sys_menus` WHERE `id`=".$this->vars["id"];
				$delete = new query($query, CONN);

				if ($delete->affectedrows() > 0) {
					//Sıralayalım
					$this->setOrders($parent);

					//Sonuç
					return true;
				}else{
					$this->error = "AN ERROR OCCURED WHILE DELETING";
					return false;
				}
			}
		}else{
			$this->error = "THIS MENU IS NOT EXISTS";
			return false;
		}
	}
	
	private function clearWaste ($ids=array()){
		new query("DELETE FROM `sys_page_file` WHERE `id` IN (".implode(",", $ids).")", CONN);
		new query("DELETE FROM `sys_content` WHERE `type`='PAGE' AND `id` IN (".implode(",", $ids).")", CONN);
		new query("DELETE FROM `sys_pages` WHERE `id` IN (".implode(",", $ids).")", CONN);
		new query("DELETE FROM `sys_permissions` WHERE `pid` IN (".implode(",", $ids).")", CONN);
	}

	public function setOrders ($parent=0, $order=0, $id=0){
		//Çekilecek id leri array'e atalım
		$ids = array();

		//Varolan sırayı seç
		$query = "SELECT `id` "
		. "FROM `sys_menus` "
		. "WHERE `parent`=".$parent." "
		. "ORDER BY `order`, `id`";
		$select = new query($query, CONN);
		while($row = $select->fetchobject()){
			if ($row->id != $id) {
				$ids[$row->id] = $row->id;
			}
		}

		//Yeni Sıra
		$t=1;

		foreach ($ids as $new_id){
			if ($t == $order) {
				$t++;
			}
			//Sorgusu
			new query("UPDATE `sys_menus` SET `order`=".$t." WHERE `id`=".$new_id, CONN);

			//Normal sırayı artırıyoruz
			$t++;
		}
	}

	public function showError (){
		return $this->error;
	}
	
	public function __destruct (){
	 
	}
}
?>
