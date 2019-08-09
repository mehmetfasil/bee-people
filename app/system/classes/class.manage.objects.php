<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Manage Objects
 * @version 1.0
 */

class manageObject {
 private $vars = array(
  "id"=>0,
  "name"=>"",
  "pass"=>"",
  "fullname"=>"",
  "detail"=>null,
  "email"=>"",
  "question"=>0,
  "answer"=>"",
  "pass_expiration"=>0,
  "cpnl"=>1,
  "level"=>1,
  "lock"=>0,
  "active"=>1,
  "groups"=>array(),
  "members"=>array()
 );
	private $error;

	public function __construct ($vars=array()){
		//Başlattık..
		if (is_array($vars) and count($vars) > 0) {
 		$this->vars = $vars;
		}
	}

	public function setObjectId ($value){
		$this->vars["id"] = is_numeric($value) ? $value : 0;
	}

	/**
	 * Object ID
	 *
	 * @return integer
	 */
	public function getObjectId (){
		return $this->vars["id"];
	}

	public function setObjectName ($value){
		$this->vars["name"] = $value;
	}

	public function setObjectPass ($value){
		$this->vars["pass"] = $value;
	}

	public function setObjectFullname ($value){
		$this->vars["fullname"] = $value;
	}

	public function setObjectDetail ($value){
		$this->vars["detail"] = $value;
	}

	public function setObjectEmail ($value){
		$this->vars["email"] = $value;
	}

	public function setObjectQuestion ($value){
		$this->vars["question"] = is_numeric($value) ? $value : 0;
	}

	public function setObjectAnswer ($value){
		$this->vars["answer"] = $value;
	}

	public function setObjectPassExpiration ($value){
	 $array = array(0,1);
		$this->vars["pass_expiration"] = in_array($value, $array) ? $value : current($array);
	}

	public function setObjectCpnl ($value){
	 $array = array(0,1);
		$this->vars["cpnl"] = in_array($value, $array) ? $value : current($array);
	}

	public function setObjectLevel ($value){
	 $array = array(1,2,3,0);
		$this->vars["level"] = in_array($value, $array) ? $value : current($array);
	}

	public function setObjectLock ($value){
	 $array = array(0,1);
		$this->vars["lock"] = in_array($value, $array) ? $value : current($array);
	}

	public function setObjectStatus ($value){
	 $array = array(0,1);
		$this->vars["active"] = in_array($value, $array) ? $value : current($array);
	}

	public function setGroups ($value){
		$this->vars["groups"] = is_array($value) ? $value : array();
	}

	public function setMembers ($value){
		$this->vars["members"] = is_array($value) ? $value : array();
	}

	/**
	 * Nesne Varlığı Kontrolü
	 *
	 * @param string $name
	 * @return integer
	 */
	private function checkObject (){
		//Sorgu
		$query = "SELECT `type` "
		. "FROM `sys_objects` "
		. "WHERE `id`=".$this->vars["id"];

		$select = new query($query, CONN);
		
		if ($select->numrows() > 0) {
		 $row = $select->fetchobject();
			return $row->type;
		}else{
			return false;
		}
	}

	/**
	 * E-Posta Adresi Kontrolü
	 *
	 * @param string $name
	 * @return integer
	 */
	public function checkEmail (){
		//Sorgu
		$query = "SELECT `id` "
		. "FROM `sys_users` "
		. "WHERE `email`='".$this->vars["email"]."' "
		. "AND `id`!=".$this->vars["id"];
		$select = new query($query, CONN);

		if ($select->numrows() > 0) {
			return false;
		}else{
			return true;
		}
	}

	/**
	 * Kullanıcı Adı Kontrolü
	 *
	 * @param string $name
	 * @return integer
	 */
	public function checkUserName ($name){
		//Dönecek sonuç
		$result = 0;

		//Sorgu
		$query = "SELECT `id` "
		. "FROM `sys_users` "
		. "WHERE `name`='".$name."'";
		$select = new query($query, CONN);
		while ($row = $select->fetchobject()) {
			$result = $row->id;
		}

		return $result;
	}
	
	/**
	 * Geçerlilik Kontrolü
	 *
	 * @param string $target
	 * @return boolean
	 */
	public function isValid ($target){
	 $pattern = ($target == "name") ? "/^[a-zA-Z0-9_]+$/" : "/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
	 
	 if (preg_match($pattern, $this->vars[$target])) {
	 	return true;
	 }
	 
	 return false;
	}

	/**
	 * Grup Adı Kontrolü
	 *
	 * @param string $name
	 * @return integer
	 */
	public function checkGroupName ($name){
		//Dönecek sonuç
		$result = 0;

		//Sorgu
		$query = "SELECT `id` "
		. "FROM `sys_groups` "
		. "WHERE `name`='".$name."'";
		$select = new query($query, CONN);
		while ($row = $select->fetchobject()) {
			$result = $row->id;
		}

		return $result;
	}

	/**
	 * Obje Kaydı
	 *
	 * @return boolean
	 */
	public function save ($type="user"){
	 if ($type == "user") {
	 	//Kullanıcı
	 	if (strlen($this->vars["name"]) > 0) {
	 	 if ($this->isValid("name")) {
 	 	 if ($this->isValid("email")) {
  	 	 if ($this->checkEmail()) {
  	 	  if ($this->vars["id"] > 0 and $this->checkObject()) {
  	 	   //İşlenen
  	 	   $affected = 0;
  
  	 	   //Update
  	 	   $query = "UPDATE `sys_users` SET ";
  	 	   if (strlen($this->vars["pass"]) > 0){
  	 	    $query.= "`pass`='".md5($this->vars["pass"])."', ";
  	 	   }
  	 	   $query.= "`fullname`='".$this->vars["fullname"]."', "
  	 	   . "`detail`='".mysql_escape_string($this->vars["detail"])."', "
  	 	   . "`question`=".$this->vars["question"].", "
  	 	   . "`answer`='".mysql_escape_string($this->vars["answer"])."', "
  	 	   . "`cpnl`='".$this->vars["cpnl"]."', "
  	 	   . "`pass_expiration`=".($this->vars["pass_expiration"]==1 ? "DATE_ADD(NOW(), INTERVAL 3 MONTH)" : "null").", "
  	 	   . "`level`=".$this->vars["level"].", "
  	 	   . "`lock`='".$this->vars["lock"]."' "
  	 	   . "WHERE `id`=".$this->vars["id"];
  	 	   $save = new query($query, CONN);
  
  	 	   if ($save->affectedrows() > 0) {
  	 	    $affected++;
  	 	   }
  
  	 	   //Durumu değişikliği..
  	 	   $query = "UPDATE `sys_objects` SET "
  	 	   . "`active`='".$this->vars["active"]."' "
  	 	   . "WHERE `id`=".$this->vars["id"];
  	 	   $save = new query($query, CONN);
  
  	 	   if ($save->affectedrows() > 0) {
  	 	    $affected++;
  	 	   }
  
  	 	   //Grup üyelikleri..
  	 	   $affected += $this->subscribeGroups($this->vars["id"]);
  
  	 	   if ($affected > 0) {
  	 	    return true;
  	 	   }else{
  	 	    $this->error = "MADE NO UPDATE";
  	 	    return false;
  	 	   }
  	 	  }else{
  	 	   if ($this->checkUserName($this->vars["name"]) == 0) {
 	 	     if (strlen($this->vars["pass"]) > 0) {
 	 	      //Insert
 	 	      $query = "INSERT INTO `sys_objects` VALUES ("
 	 	      . "null, "
 	 	      . "NOW(), "
 	 	      . "'user', "
 	 	      . $_SESSION["SYS_USER_ID"].", "
 	 	      . "'".$this->vars["active"]."'"
 	 	      . ")";
 	 	      $save = new query($query, CONN);
 
 	 	      if($save->affectedrows() > 0){
 	 	       //Eklenmiş ID
 	 	       $this->vars["id"] = $save->insertid();
 
 	 	       //Bilgileri ekleyelim
 	 	       $query = "INSERT INTO `sys_users` VALUES ("
 	 	       . $this->vars["id"].", "
 	 	       . "'".$this->vars["name"]."', "
 	 	       . "'".md5($this->vars["pass"])."', "
 	 	       . "'".$this->vars["fullname"]."', "
 	 	       . "'".mysql_escape_string($this->vars["detail"])."', "
 	 	       . "'".$this->vars["email"]."', "
 	 	       . $this->vars["question"].", "
 	 	       . "'".mysql_escape_string($this->vars["answer"])."', "
 	 	       . "'".$this->vars["cpnl"]."', "
 	 	       . ($this->vars["pass_expiration"]==1 ? "DATE_ADD(NOW(), INTERVAL 3 MONTH)" : "null").", "
 	 	       . $this->vars["level"].", "
 	 	       . "'0'"
 	 	       . ")";
 	 	       $save = new query($query, CONN);
 
 	 	       if($save->affectedrows() > 0){
 	 	        //Grup üyelikleri..
 	 	        $this->subscribeGroups($this->vars["id"]);
 
 	 	        return true;
 	 	       }else{
 	 	        $this->error = "USER CANNOT INSERTED";
 	 	        return false;
 	 	       }
 	 	      }else{
 	 	       $this->error = "OBJECT CANNOT INSERTED";
 	 	       return false;
 	 	      }
 	 	     }else{
 	 	      $this->error = "PASSWORD MUST BE FILLED";
 	 	      return false;
 	 	     }
  	 	   }else{
  	 	    $this->error = "USER NAME EXISTS";
  	 	    return false;
  	 	   }
  	 	  }
  	 	 }else{
  	 	  $this->error = "USER EMAIL EXISTS";
  	 	  return false;
  	 	 }
 	 	 }else{
 	 	  $this->error = "USER EMAIL IS INVALID";
 	 	  return false;
 	 	 }
	 	 }else{
	 	  $this->error = "USER NAME CONTAINS INVALID CHARACTERS";
	 	  return false;
	 	 }
	 	}else{
	 	 $this->error = "USER NAME MUST BE FILLED";
	 	 return false;
	 	}
	 }else{
	  //Grup
	  if (strlen($this->vars["name"]) > 0) {
	 	 if ($this->isValid("name")) {
 	   if (($this->vars["id"] > 0) and $this->checkObject()){
 	    //İşlenen
 	    $affected = 0;
 
 	    //Update
 	    $query = "UPDATE `sys_groups` SET "
 	    . "`fullname`='".$this->vars["fullname"]."' "
 	    . "`detail`='".mysql_escape_string($this->vars["detail"])."' "
 	    . "WHERE `id`=".$this->vars["id"];
 	    $save = new query($query, CONN);
 
 	    if ($save->affectedrows() > 0) {
 	     $affected++;
 	    }
 
 	    //Durumu değişikliği..
 	    $query = "UPDATE `sys_objects` SET "
 	    . "`active`='".$this->vars["active"]."' "
 	    . "WHERE `id`=".$this->vars["id"];
 	    $save = new query($query, CONN);
 
 	    if ($save->affectedrows() > 0) {
 	     $affected++;
 	    }
 
 	    //Grup üyeleri..
 	    $affected += $this->subscribeMembers($this->vars["id"]);
 
 	    if ($affected > 0) {
 	     return true;
 	    }else{
 	     $this->error = "MADE NO UPDATE";
 	     return false;
 	    }
 	   }else{
 	    if ($this->checkGroupName($this->vars["name"]) == 0) {
 	     //Insert
 	     $query = "INSERT INTO `sys_objects` VALUES ("
 	     . "null, "
 	     . "NOW(), "
 	     . "'group', "
 	     . $_SESSION["SYS_USER_ID"].", "
 	     . "'".$this->vars["active"]."'"
 	     . ")";
 	     $save = new query($query, CONN);
 
 	     if($save->affectedrows() > 0){
 	      //Eklenmiş ID
 	      $this->vars["id"] = $save->insertid();
 
 	      //Bilgileri ekleyelim
 	      $query = "INSERT INTO `sys_groups` VALUES ("
 	      . $this->vars["id"].", "
 	      . "'user', "
 	      . "'".$this->vars["name"]."', "
 	      . "'".$this->vars["fullname"]."', "
 	      . "'".mysql_escape_string($this->vars["detail"])."'"
 	      . ")";
 	      $save = new query($query);
 
 	      if($save->affectedrows() > 0){
 	       //Grup üyeleri..
 	       $this->subscribeMembers($this->vars["id"]);
 
 	       return true;
 	      }else{
 	       $this->error = "GROUP CANNOT INSERTED";
 	       return false;
 	      }
 	     }else{
 	      $this->error = "OBJECT CANNOT INSERTED";
 	      return false;
 	     }
 	    }else{
 	     $this->error = "GROUP NAME EXISTS";
 	     return false;
 	    }
 	   }
 	  }else{
 	   $this->error = "GROUP NAME CONTAINS INVALID CHARACTERS";
 	   return false;
 	  }
	  }else{
	   $this->error = "GROUP NAME MUST BE FILLED";
	   return false;
	  }
	 }
	}

	/**
	 * Grup ya da kullanıcı siler
	 *
	 * @param integer $id
	 * @return boolean
	 */
	public function delete (){
	 if ($type = $this->checkObject()){
			//Silelim
			$query = "DELETE FROM `sys_objects` WHERE `id`=".$this->vars["id"];
			$delete = new query($query, CONN);
			
			if ($delete->affectedrows() > 0) {
			 if ($type == "user") {
  			//Kullanıcı bilgisi
  			new query("DELETE FROM `sys_users` WHERE `id`=".$this->vars["id"]);
  			new query("DELETE FROM `sys_group_members` WHERE `member`=".$this->vars["id"]);
			 }else{
  			//Grup bilgisi
  			new query("DELETE FROM `sys_groups` WHERE `id`=".$this->vars["id"]);  			
  			new query("DELETE FROM `sys_group_members` WHERE (`gid`=".$this->vars["id"]." OR `member`=".$this->vars["id"].")");
			 }
 			//Yetkiler
 			new query("DELETE FROM `sys_permissions` WHERE `oid`=".$this->vars["id"]);
 			
 			return true;
			}else{
 			$this->error = "OBJECT NOT DELETED";
 			return false;
			}
	 }else{
			$this->error = "OBJECT NOT FOUND";
			return false;
	 }
	}

	private function subscribeGroups ($member){
		//İşlenen
	 $affected = 0;
		
		//Önceki üyeliklerini silelim
		$query = "DELETE FROM `sys_group_members` WHERE `member`=".$member;
		$delete = new query($query, CONN);
		
		if ($delete->affectedrows() > 0) {
			$affected++;
		}
		
		if (count($this->vars["groups"]) > 0) {
		 //Gruplar
		 $ids = array();
		 
 		//ID'leri çekelim
 		$query = "SELECT `o`.`id` "
 		. "FROM `sys_objects` `o` "
 		. "INNER JOIN `sys_groups` `g` ON (`o`.`id`=`g`.`id`) "
 		. "WHERE `o`.`active`='1' "
 		. "AND `g`.`name` IN ('".implode("','", $this->vars["groups"])."')";
 		$select = new query($query, CONN);
 		while ($row = $select->fetchobject()) {
 			$ids[$row->id] = $row->id;
 		}
 
 		//Ekleyelim..
 		foreach ($ids as $gid){
				//Sorgu
				$query = "INSERT INTO `sys_group_members` VALUES ("
				. $gid.", "
				. $member.", "
				. "NOW()"
				. ")";
				$save = new query($query, CONN);
				
				if ($save->affectedrows() > 0) {
					$affected++;
				}
			}
		}
		
		return $affected;
	}

	private function subscribeMembers ($gid){
		//İşlenen
	 $affected = 0;
		
	 //Önceki üyeliklerini silelim
	 $query = "DELETE FROM `sys_group_members` WHERE `gid`=".$gid;
		$delete = new query($query, CONN);
		
		if ($delete->affectedrows() > 0) {
			$affected++;
		}
		
		if (count($this->vars["members"]) > 0) {
		 //Gruplar
		 $ids = array();
		 
 		//ID'leri çekelim
 		$query = "SELECT `o`.`id` "
 		. "FROM `sys_objects` `o` "
 		. "INNER JOIN `sys_users` `u` ON (`o`.`id`=`u`.`id`) "
 		. "WHERE `o`.`active`='1' "
 		. "AND `o`.`id` IN (".implode(",", $this->vars["members"]).")";
 		$select = new query($query, CONN);
 		while ($row = $select->fetchobject()) {
 			$ids[$row->id] = $row->id;
 		}

 		//Ekleyelim..
 		foreach ($ids as $member){
				//Sorgu
				$query = "INSERT INTO `sys_group_members` VALUES ("
				. $gid.", "
				. $member.", "
				. "NOW()"
				. ")";
				$save = new query($query, CONN);
				
				if ($save->affectedrows() > 0) {
					$affected++;
				}
			}
		}
		
		return $affected;
	}

	public function showError (){
		return $this->error;
	}
	
	public function __destruct (){
	 
	}
}
?>