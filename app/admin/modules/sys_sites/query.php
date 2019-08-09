<? 
defined("PASS") or die("Giriş Engellendi");

// xml var
$xml = "";
# MySQLi connection
$mysqli = new mysqli(MYHOST,MYUSER,MYPASS,MYDB);
$mysqli->query("SET NAMES 'utf8' COLLATE 'utf8_turkish_ci'");
if(ACT){
	switch (ACT){
		
			case "list":
			
			$query =" SELECT s.id,s.name,s.title,";
			$query.=" m.id AS menu_id,m.name AS menu_name,m.caption AS menu_caption";
			$query.=" FROM sys_sites AS s";
			$query.=" LEFT JOIN sys_sitemenus AS sm ON(sm.site_id=s.id) ";
			$query.=" LEFT JOIN sys_menus AS m ON(sm.menu_id=m.id) ";
			$query.=(int)getvalue("id",0)!=0 ? " WHERE s.id=".getvalue("id",0) : "";
			$query.=(int)getvalue("groupby")!=0 ? "GROUP BY s.id" : "";
			$query.=" ORDER BY s.id DESC";
			$total = $mysqli->query($query)->num_rows;			
			$xml.="<stat total='".$total."' x='".X."' y='".Y."' />";
			$query.=" LIMIT ".X.",".Y;

			$result=$mysqli->query($query);
			if($result){
				while ($row = $result->fetch_object()) {
					$xml.="<item>";
					$xml.="<id>".$row->id."</id>";
					$xml.="<name><![CDATA[".$row->name."]]></name>";
					$xml.="<title><![CDATA[".$row->title."]]></title>";
					$xml.="<menu_id><![CDATA[".$row->menu_id."]]></menu_id>";
					$xml.="<menu_name><![CDATA[".$row->menu_name."]]></menu_name>";
					$xml.="<menu_caption><![CDATA[".$row->menu_caption."]]></menu_caption>";
					$xml.="</item>";
				}
			}
			
			break;
		
		case "save":
			if(!strlen(getvalue("name")) > 0){
				$xml.="<result status='ERROR'><![CDATA[Lütfen İsim Giriniz !]]></result>";
			}else if(!strlen(getvalue("title")) > 0){
				$xml.="<result status='ERROR'><![CDATA[Lütfen Açıklama Giriniz !]]></result>";
			}else{
				$id=(int)getvalue("id",0);
				$isExist=showValue("sys_sites","COUNT(id)","id=".$id);
				if($isExist){
					$query =" UPDATE sys_sites";
					$query.=" SET name='".getvalue("name")."',";
					$query.=" title='".getvalue("title")."'";
				}else{
					$query =" INSERT INTO sys_sites";
					$query.=" (name,title)";
					$query.=" VALUES ('".getvalue("name")."','".getvalue("title")."')";
				}
				
				$save = new query($query);
				if($save->affectedrows()>0){
					$xml.="<result status='OK'><![CDATA[Başarıyla Kaydedildi]]></result>";
				}else{
					$xml.="<result status='OK'><![CDATA[Değişiklik Yapılmadı]]></result>";					
				}
			}
			break;
		
		case "delete":
			$isExist = (int)showValue("sys_sites","COUNT(id)","id=".getvalue("id",0));					
			if($isExist > 0){
				// trigger deletes sys_sitemenus related rows
				$query = "DELETE FROM sys_sites WHERE id=".getvalue("id",0);
				$result= $mysqli->query($query);
				if($result){
					$xml="<result status='OK' />";
				}else {
					$xml="<result status='ERROR' />";
				}
			}else{
				$xml="<result status='ERROR' />";
			}
			
			break;	
				
		
		case "getSiteMenus":
			$query = "SELECT menu_id AS id FROM sys_sitemenus WHERE site_id=".getvalue("id",0);
			$result= $mysqli->query($query);
			while ($row=$result->fetch_object()) {
				$xml.="<menu id='".$row->id."' />";
			}
			break;
		
		case "saveSiteMenu":
				$site_id			= (int)getvalue("site",0);
				$isSiteExist 	= (int)showValue("sys_sites","COUNT(id)","id=".$site_id);
				if($isSiteExist > 0){
					if(isset($_POST["menu"]) && is_array($_POST["menu"]) && count($_POST["menu"])>0){
						$query = "DELETE FROM sys_sitemenus WHERE site_id=".$site_id.";";
						foreach ($_POST["menu"] as $key=>$value) {
							$query.="INSERT INTO sys_sitemenus VALUES(".$site_id.",".$value.");";
						}
						// multi query
						$result=$mysqli->multi_query($query);

						if($result){
							$xml="<result status='OK' />";
						}else{
							$xml="<result status='ERROR' />";							
						}
					}else{
						$xml="<result status='ERROR' />";
					}
				}
			break;	
			
		case "deleteSiteMenu":
			$isExist = (int)showValue("sys_sitemenus","COUNT(site_id)","site_id=".getvalue("site_id",0)." AND menu_id=".getvalue("menu_id",0));
			if($isExist > 0){
				$query = "DELETE FROM sys_sitemenus WHERE site_id=".getvalue("site_id",0)." AND menu_id=".getvalue("menu_id");
				$result = $mysqli->query($query);
				if($result){
					$xml="<result status='OK' />";
				}else{
					$xml="<result status='ERROR' />";
				}
			}else{
					$xml="<result status='ERROR' />";
			}
			break;	
				
		default:
			break;		
	}
}
echo $xml;
?>