<?php
/**
 * @copyright Masters 2019
 * @author Fa.Ce.
 * @abstract Yetkili Menüler
 * @version 1.0
 */

class qmenu {
	private $userid;
	private $userlevel;

	public function __construct ($userid=0, $userlevel=0){
		$this->userid = $userid;
		$this->userlevel = $userlevel;
	}
	
	/**
	 * @param type String
	 * @param parent Integer
	 * @param bypermission boolean
	 * @abstract bypermission true oldugunda kullanıcının yetkisi oldugu menuler gelir
	 * @see bypermission parametresi eklendi
	 * 15/04/2009 20:54
	 * */
	public function qgetMenus ($type="site", $parent, $bypermission=false){
		$menus = array();
		
		//Sorgusu
		if($bypermission){
			$query = "SELECT `m`.* "
			. "FROM `sys_menus` AS `m` "
			. "WHERE `active`='1' "
			. "AND `m`.`type`='".$type."' "
			. "AND `m`.`visibility`='visible' "
			. "AND `m`.`level`=0 "
			. "UNION "
			. "SELECT "
			. "`m`.* "
			. "FROM sys_menus AS `m` "
			. "JOIN sys_pages AS `p` ON (`p`.`mid`=`m`.`id` AND `p`.`sid`='index' ) "
			. "JOIN sys_permissions AS `perm` ON (`p`.`id`=`perm`.`pid` AND `perm`.`oid`=".$_SESSION["SYS_USER_ID"]." AND `perm`.`permit` IN (1000,1100,1110,1111)) "
			. "WHERE level > 0 "
			. "AND `m`.`type`='".$type."' "
			. "AND `m`.`active`='1' "
			. "AND `m`.`visibility`='visible' "
			. "AND `m`.`level` BETWEEN 1 AND ".$this->userlevel." "			
			. "ORDER BY `parent`,`order`";
		}else{
			$query = "SELECT `m`.* "
			. "FROM `sys_menus` AS `m` "
			. "WHERE `active`='1' "
			. "AND `m`.`type`='".$type."' "
			. "AND `m`.`visibility`='visible' "
			. "AND `m`.`level` <= ".$this->userlevel." "
			. "ORDER BY `m`.`parent`, `m`.`order`";			
		}

		$select = new query($query, CONN);
		//echo mysql_error();
		while ($row = $select->fetchobject()) {
			$menus[$row->parent][$row->id]["icon"]    = $row->icon;
			$menus[$row->parent][$row->id]["caption"] = label(stripslashes($row->caption));
			$menus[$row->parent][$row->id]["link"]    = ($type == "admin" ? str_replace("?", "?".ADMIN_EXTENSION."&", stripslashes($row->link)) : stripslashes($row->link));
			$menus[$row->parent][$row->id]["target"]  = $row->target;
			$menus[$row->parent][$row->id]["split"]   = $row->split;
			$menus[$row->parent][$row->id]["detail"]   = $row->detail;
		}
		
		return $this->qmenuScript($menus, $parent);
	}
	
	/**
	 * $type değişkeni paramatre olarak eklendi ,
	 * alt menu isimlerini site sayfalarında cagırmak icin
	 * 07/04/2009 00:21
	 * */
	public function qgetSubMenus ($mid=0,$type="admin"){
		$menus = array();
		
		if ($mid > 0) {
			//Sorgusu
			$query = "SELECT `mid`, `sid`, `caption`, `icon` "
			. "FROM `sys_pages` "
			. "WHERE `mid`=".$mid." "
			. "AND `visibility`='visible' "
			. "ORDER BY `id`";
			$select = new query($query);
			while ($row = $select->fetchobject()) {
				$menus[$row->sid]["caption"] = label(stripslashes($row->caption));
				$menus[$row->sid]["icon"] = $row->icon;
				$menus[$row->sid]["link"] = CONF_MAIN_PAGE.($type == "admin" ? "?".ADMIN_EXTENSION."&amp;" : "?")."pid=".$row->mid."&amp;sid=".$row->sid;
			}
		}

		return $this->qsubmenuScript($menus);
	}
	
	
	private function qsubmenuScript ($submenu){
		$result = "";
	 if (count($submenu) > 0) {
	  $result.= "<ul class=\"submenu\">\n";
	 	foreach ($submenu as $key=>$value){
	 	 $result.= "<li>\n<a title='".$value["caption"]."' href=\"".$value["link"]."\" style=\"background:url(objects/icons/16x16/".$value["icon"].") no-repeat left center; padding-left:20px\">"
	 	 				.label($value["caption"])
	 	 				."</a>\n</li>\n";
	 	}
	 	$result.= "</ul>\n";
	 }
	 return $result;
	}

	public function qmenuIcon ($icon){
		if (!empty($icon) and ($icon != "spacer.png") and ($icon != "spacer.gif")){
			$menu_icon = "'<img src=\"objects/icons/16x16/".$icon."\" height=\"16\" alt=\"\" />'";
		}else{
			$menu_icon = "null";
		}

		return $menu_icon;
	}

	public function qmenuLink ($link){
		if (!empty($link)){
			$menu_link = "".$link."";
		}else{
			$menu_link = "null";
		}

		return $menu_link;
	}

	public function qmenuTarget ($target){
		switch ($target){
			case "_self":
				$menu_target = "_self";
				break;
			case "_blank":
				$menu_target = "_blank";
				break;
			default:
				$menu_target = "null";
		}

		return $menu_target;
	}

	public function qmenuSplit ($split){
		if ($split == "1"){
			$menu_split = "<li><span class=\"qmdivider qmdividerx\" ></span></li>";
		}else{
			$menu_split = "";
		}

		return $menu_split;
	}

	private function qmenuScript($menu, $parent){
		$result = "";
		foreach ($menu as $id=>$nextarray){
			//Ana Menüler
			if ($id == $parent){
			 $i = 0;
				foreach ($nextarray as $key=>$value){

					$result.= "<li><a href=\"".$this->qmenuLink($value["link"])."\" target=\"".$this->qmenuTarget($value["target"])."\" title=\"".$value["detail"]."\">".$value["caption"]."</a>";
					if (key_exists($key, $menu)){
						$result.= "<ul>".$this->qmenuScript($menu, $key)."</ul>";
					}else{
						$result.= "</li>";
					}
					//Ayıraç
					//$result.= $this->qmenuSplit($value["split"]);
					$i++;
					
					if ($i < count($nextarray)) {
						$result.= "";
					}
				}
			}
		}
		return $result;
	}
}
?>