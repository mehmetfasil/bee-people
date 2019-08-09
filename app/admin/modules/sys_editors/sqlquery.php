<? 
header("Content-type:text/xml;charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
echo "<content>";
defined("PASS") or die("Dosya Yok !");

$xml="";
if(ACT){
	// mysqli
	$mysqli = new mysqli(MYHOST, MYUSER, MYPASS);
	// collation
	$mysqli->query("SET NAMES 'utf8' COLLATE 'utf8_turkish_ci'");
	// database
	$db=getvalue("db");
	// tablo
	$table=getvalue("table");
	switch(ACT){
		case "dbtree":
			$path=getvalue("path","");
			if(!strlen($path)>0){
				// yetkili databaseleri cekiyoruz
				$query = "SHOW DATABASES";
				$result = $mysqli->query($query);
				if($result->num_rows>0){
					$xml="<list>";
					while ($row=$result->fetch_object()) {
						$xml.= "<item><label>".$row->Database."</label></item>";
					}
					$xml.="</list>";
				}
			}else{
				$path=explode("/",$path);
				// eger db secilmisse
				if(is_array($path) && count($path)==1){
					$db=$path[0];
					// tables
					$tables=array();
					$query = "USE ".$db;
					$mysqli->query($query);
					$query = "SHOW FULL TABLES;";
					$result=$mysqli->query($query);
					if ($result->num_rows > 0) {
						// views sayısı
						$v=0;
						// table sayısı
						$t=0;
						// butun tablolar
						while ($row = $result->fetch_row()) {
							if($row[1]=="VIEW" || $row[1]=="SYSTEM VIEW"){
								$type="view";
								$v++;
							}else{
								$type="table";
								$t++;
							}
							array_push($tables,array(($type)=>$row[0]));
						}

						$xml ="<list>";
						$xml.="<item><label>Tablolar</label>";
						$t>0 ? $xml.="<list>" : "";
						foreach ($tables as $item){
							if(isset($item["table"])){
								$xml.="<item async='0'><label>".$item["table"]."</label></item>";
							}
						}
						$t>0 ? $xml.="</list>" : "";
						$xml.="</item>";
						$xml.="<item><label>Görünümler</label>";
						$v>0 ? $xml.="<list>" : "";
						foreach ($tables as $type=>$item){
							if(isset($item["view"])){
								$xml.="<item async='0'><label>".$item["view"]."</label></item>";
							}
						}
						$v>0 ? $xml.="</list>" : "";
						$xml.="</item>";
						$xml.="</list>";
					}
				}
			}
			break;

		case "listTables":
			if(strlen($db)>0){
				// ilk once veritabanını seciyoruz
				$query = "USE ".$db;
				if($mysqli->query($query)){
					// tabloları gosteriyoruz
					$query = "SHOW FULL TABLES WHERE INSTR(Table_type,'".(getvalue("type","table")=="table" ? "TABLE" : "VIEW")."')>0 ";
					$result=$mysqli->query($query);
					if($result && $result->num_rows > 0){
						$tables=array();
						while ($row = $result->fetch_row()) {
							array_push($tables,$row[0]);
						}
						$query = "SHOW TABLE STATUS WHERE Name IN('".implode("','",$tables)."')";
						$result=$mysqli->query($query);
						if($result && $result->num_rows>0){
							$xml = "<list>";
							while ($row = $result->fetch_object()) {
								$xml.="<item rows='".$row->Rows."' engine='".$row->Engine."' collation='".$row->Collation."' length='".number_format($row->Data_length,null,",",null)."'>";
								$xml.="<![CDATA[".$row->Name."]]>";
								$xml.="</item>";
							}
							$xml.="</list>";
						}
					}
				}

			}
			break;

		case "getTableData":
			if(strlen($db)>0 && strlen($table)>0){
				// db yi seciyoz
				$query = "USE ".getvalue("db");
				if($mysqli->query($query)){
					// fields
					$query = "SHOW FIELDS FROM ".$table;
					$result= $mysqli->query($query);
					if($result && $result->num_rows > 0){
						// fields 
						$fields=array();
						// json_encode yok ise 
						if(!function_exists("json_encode")){
							require_once(CONF_DOCUMENT_ROOT.DS."system".DS."classes".DS."class.json.php");
							$json=new Services_JSON();
						}
							
						$xml.="<fields>";
						while ($row = $result->fetch_object()) {
							// diziye ekliyoruz sutunları
							array_push($fields,$row->Field);
							// field ozellikleri
							$options=array();
							// tur
							$type=explode(" ",$row->Type);
							// unsigned mı ?
							$unsigned=in_array("unsigned",$type) ? 1 : 0;
							// uzunluk
							$length=preg_match("([(0-9]{1}+\d+[0-9)]{1}+)",$type[0],$matches) ? str_replace(array("(",")"),"",$matches[0]) : 0;
							// enum degerleri
							$values=preg_match("/(^enum|^set)\((.*?)\)$/",$row->Type,$matches2) ? str_replace(array("(",")","'"),"",$matches2[2]) : "";
							// type son hal
							$type=preg_replace("/\((.*?)$/","",$row->Type);
							//$type=strstr($type[0],"(") ? str_replace(array(strstr($type[0],"("),"unsigned"),"",$row->Type) : $type[0];

							// ozellikleri diziye aktarıyoz
							$options["type"]=$type;
							$options["Null"]=$row->Null;
							$length>0 ? $options["length"]=$length : "";
							$row->Key!="" ? $options["key"]=$row->Key : "";
							$unsigned==1 ? $options["unsigned"]=1 : "";
							$values!="" ? $options["values"]=$values : "";
							$row->Default!="" ? $options["defaultvalue"]=$row->Default : "";
							// xml verisi
							
							$xml.="<field options='".(function_exists("json_encode") ? json_encode($options) : $json->encode($options))."'>";
							$xml.="<![CDATA[".$row->Field."]]>";
							$xml.="</field>";
						}
						$xml.="</fields>";
					}
					// data
					$query =" SELECT * FROM ".$table;
					$result= $mysqli->query($query);
					if($result && $result->num_rows>0){
						// toplam kayıt
						$total=$result->num_rows;
						// sıralama 
						$query.=" ORDER BY ".$fields[0]." DESC";
						// sorgu devam
						$query.=" LIMIT ".getvalue("x",X).",".getvalue("y",Y);
						$result= $mysqli->query($query);
						if($result && $result->num_rows>0){
							// paging
							$xml.="<stat total='".$total."' x='".getvalue("x",X)."' y='".getvalue("y",Y)."' />";
							// data
							$xml.="<list>";
							while ($row = $result->fetch_object()) {
								$xml.="<item>";
								foreach ($row as $field=>$data) {
									$xml.="<".$field.">";
									$xml.="<![CDATA[".htmlentities($data,null,"utf-8")."]]>";
									$xml.="</".$field.">";
								}
								$xml.="</item>";
							}
							$xml.="</list>";
						}
					}
				}
			}
			break;

		case "update":
			if(strlen($db)>0 && strlen($table)>0)
			if(count($_POST)>0){
				// db seciyoruz
				$query = "USE ".$db;
				if($mysqli->query($query)){
					// sutunların tiplerini cekiyoruz
					$query = "SHOW FIELDS FROM ".$table;
					$result = $mysqli->query($query);
					if($result){
						$fields=array();
						$yuniks=array();
						while ($row = $result->fetch_object()) {
							$fields[$row->Field]=array("type"=>$row->Type,"default"=>$row->Default);
							$row->Key=="UNI" || $row->Key=="PRI" ? array_push($yuniks,$row->Field) : "";
						}

						$query = "SELECT * FROM ".$table;
						$condition = array();
						// eger tabloda yunik veya primary alan var ise ilk once ona gore şart ifademizi oluşturuyoruz
						if(count($yuniks)>0){
							foreach ($yuniks as $field){
								if(isset($_POST["old_".$field])){
									$value=trim($_POST["old_".$field]);
									if(strstr($fields[$field]["type"],"int") && $value!=""){
										array_push($condition,"`".$field."`=".(int)$value);
									}else if($value!=""){
										array_push($condition,"`".$field."`='".mysql_real_escape_string($value)."'");
									}
								}
							}
						}else { // yunik alan yok ise onceki verilere gore şart ifadesini olusturuyoruz
							foreach ($_POST as $key=>$value){
								if(strstr($key,"old_")){
									$field=str_replace("old_","",$key);
									$value=trim($value);
									// boyle bir sutun var mı
									if(array_key_exists($field,$fields)){
										if(strstr($fields[$field]["type"],"int") && $value!=""){
											array_push($condition,"`".$field."`=".(int)$value);
										}else if($value!=""){
											array_push($condition,"`".$field."`='".mysql_real_escape_string($value)."'");
										}
									}
								}
							}
						}

						$result = $mysqli->query($query." WHERE ".implode(" AND ",$condition));
						if($result && $result->num_rows>0 && getvalue("progress","update")=="update"){ // boyle bir satır varsa ve işlem update ise
							// update ifadesini hazırlıyoruz
							$update=array();
							foreach ($_POST as $key=>$value){
								if(strstr($key,"new_")){
									$field=str_replace("new_","",$key);
									$value=trim($value);
									// boyle bir sutun var mı
									if(array_key_exists($field,$fields)){
										if(strstr($fields[$field]["type"],"int") && $value!=""){
											array_push($update,"`".$field."`=".(int)$value);
										}else if($value!=""){
											array_push($update,"`".$field."`='".mysql_real_escape_string($value)."'");
										}
									}
								}
							}

							// update var ise
							if(count($update)>0){
								// ee artık update edelim
								$query = "UPDATE `".$table."` SET ".implode(",",$update)." WHERE ".implode(" AND ",$condition);
								$update= $mysqli->query($query);
								if($update){
									if($mysqli->affected_rows>0){
										$xml="<result type='OK'><![CDATA[Başarıyla Kaydedildi]]></result>";
									}else{
										$xml="<result type='ERROR'><![CDATA[Değişiklik Yapılmadı]]></result>";
									}
								}else{
									$xml="<result type='ERROR'><![CDATA[Güncelleme Esnasında Hata Oluştu : ".$mysqli->error."]]></result>";
								}
							}else{
								$xml="<result type='ERROR'><![CDATA[Değişiklik Yapılmadı]]></result>";
							}
						}else{ // satır olmadıgı icin insert edecez
							// columns,values ifadesi
							$columns=array();
							$values=array();
							foreach ($_POST as $key=>$value){
								// sutun ismi old_ veya new_ ile baslıyor ve daha once columns dizisine eklenmemisse
								if(strstr($key,"old_") ||strstr($key,"new_") && !in_array(str_replace(array("old_","new_"),"",$key),$columns)){
									$field=str_replace(array("old_","new_"),"",$key);
									// varsa yenisini yoksa eskisini alıyoruz
									$value=strstr($key,"old_") && isset($_POST["new_".$field]) ? $_POST["new_".$field] : $value;
									$value=trim($value);
									// boyle bir sutun var mı
									if(array_key_exists($field,$fields)){
										// sutunlara ekliyoruz
										array_push($columns,$field);
										// verilere ekliyoruz
										if(strstr($fields[$field]["type"],"int") && $value!=""){ // int ise
											array_push($values,(int)$value);
										}else {
											$value=="" ? $fields[$field]["default"] : $value;
											array_push($values,"'".mysql_real_escape_string($value)."'");
										}
									}
								}
							}
							// eger sutunlar ve veriler varsa
							if(count($columns)>0 && count($values)>0){								
								$query = "INSERT INTO `".$table."` (`".implode("`,`",$columns)."`) VALUES(".implode(",",$values).")";
								$insert= $mysqli->query($query);								
								if($insert){
									if($mysqli->affected_rows>0){
										$xml="<result type='OK'><![CDATA[Başarıyla Kaydedildi]]></result>";
									}else{
										$xml="<result type='OK'><![CDATA[Herhangi bir değişiklik yapılmadı]]></result>";
									}
								}else{
									$xml="<result type='ERROR'><![CDATA[".$mysqli->errno." : ".$mysqli->error."]]></result>";									
								}
							}else{
								$xml="<result type='ERROR'><![CDATA[Insert ifadesi için gerekli olan sütun ve veriler yok !]]></result>";
							}
						}
					}else{
						$xml="<result type='ERROR'><![CDATA[Tablonun meta bilgileri alınırken hata oluştu : ".$mysqli->error."]]></result>";
					}
				}else{
					$xml="<result type='ERROR'><![CDATA[Veritabanı Seçilemedi : ".$mysqli->error."]]></result>";
				}
			}else{
				$xml="<result type='ERROR'><![CDATA[POST data yok !]]></result>";
			}
			break;
			
		case "deleteRow":
			if(strlen($db)>0 && strlen($table)>0){
				$query = "USE ".$db;
				if($mysqli->query($query)){
					// sutunları cekiyoruz
					$query = "SHOW FIELDS FROM ".$table;
					$result = $mysqli->query($query);
					if($result && $result->num_rows>0){
						$fields=array();
						$yuniks=array();
						while ($row = $result->fetch_object()) {
							$fields[$row->Field]=array("type"=>$row->Type,"default"=>$row->Default);
							$row->Key=="UNI" || $row->Key=="PRI" ? array_push($yuniks,$row->Field) : "";
						}
						// conditions
						$condition=array();
						// eger tabloda yunik veya primary alan var ise ilk once ona gore şart ifademizi oluşturuyoruz
						if(count($yuniks)>0){
							foreach ($yuniks as $field){
								if(strlen(getvalue($field))>0){
									$value=trim(getvalue($field));
									if(strstr($fields[$field]["type"],"int") && $value!=""){
										array_push($condition,"`".$field."`=".(int)$value);
									}else if($value!=""){
										array_push($condition,"`".$field."`='".mysql_real_escape_string($value)."'");
									}
								}
							}
						}else { // yunik alan yok ise onceki verilere gore olusturuyoruz
							foreach ($_POST as $key=>$value){
								$field=$key;
								$value=trim($value);
								// boyle bir sutun var mı
								if(array_key_exists($field,$fields)){
									if(strstr($fields[$field]["type"],"int") && $value!=""){
										array_push($condition,"`".$field."`=".(int)$value);
									}else if($value!=""){
										array_push($condition,"`".$field."`='".mysql_real_escape_string($value)."'");
									}
								}
							}
						}
						
						if(count($condition)>0){ // eger şartlar varsa
							$query = "DELETE FROM `".$table."` WHERE ".implode(" AND ",$condition); 
							if($mysqli->query($query)){
								if($mysqli->affected_rows>0){
									$xml="<result type='OK' />";																								
								}else{
									$xml="<result type='ERROR'><![CDATA[Herhangi bir değişiklik yapılmadı !]]></result>";																								
								}
							}else{
								$xml="<result type='ERROR'><![CDATA[".$mysqli->errno."-".$mysqli->error."]]></result>";															
							}
						}else{
							$xml="<result type='ERROR'><![CDATA[Şart ifadesi oluşturulamadı !]]></result>";															
						}
					}else{
						$xml="<result type='ERROR'><![CDATA[Tablonun meta bilgileri alınamadı : ".$mysqli->errno."-".$mysqli->error."]]></result>";															
					}
				}else{
					$xml="<result type='ERROR'><![CDATA[Veritabanı Seçilemedi : ".$mysqli->errno."-".$mysqli->error."]]></result>";									
				}
			}else{
				$xml="<result type='ERROR'><![CDATA[Veritabanı veya tablo seçilmedi]]></result>";				
			}
			break;	
			
		case "truncateTable":
			if(strlen($db)>0 && strlen($table)>0){
				$query = "USE ".$db;
				if($mysqli->query($query)){
					$query = "TRUNCATE ".$table;
					if($mysqli->query($query)){
						$xml="<result type='OK'/>";										
					}else{
						$xml="<result type='ERROR'><![CDATA[".$mysqli->errno." : ".$mysqli->error."]]></result>";												
					}
				}else{
					$xml="<result type='ERROR'><![CDATA[".$mysqli->errno." : ".$mysqli->error."]]></result>";									
				}
			}else{
				$xml="<result type='ERROR'><![CDATA[Veritabanı veya tablo seçilmedi]]></result>";				
			}
			break;
			
		case "doQuery":
			if(strlen($db)>0){
				$query = "USE ".$db;
				if($mysqli->query($query)){
					$query = mysql_real_escape_string(getvalue("query"));						
					$result= $mysqli->query($query);
					// paging için 
					$xml.="<stat total='".$result->num_rows."' x='".getvalue("x",X)."' y='".getvalue("y",Y)."' />";
					if($result){						
						// text sutunları
						$columns_text=array();
						// sorgunun sutunları cekiyoruz
						$xml.= "<columns>";
						while ($row = $result->fetch_field()) {
							$xml.="<item ><![CDATA[".$row->name."]]></item>";
							// eger sutun text tipinde ise
							if((int)$row->type==MYSQLI_TYPE_BLOB)
								array_push($columns_text,$row->name);
						}
						$xml.= "</columns>";
						
						// veri varsa çekiyoruz
						if($result->num_rows>0){
							// limit ifadesini ekliyoruz eger sorguda limit belirtilmemisse
							$query.=!stristr(getvalue("query"),"limit") ? " LIMIT ".getvalue("x",X).",".getvalue("y",Y) : "";
							$result= $mysqli->query($query);							
							$xml.= "<list>";
							while ($row = $result->fetch_object()) {
								$xml.="<item>";
								foreach ($row as $column=>$value){
									$xml.="<".$column." >";
									if(in_array($column,$columns_text)){
										$xml.="<![CDATA[MEMO <span class='hidden'>".stripslashes($value)."</span>]]>";
									}else{
										$xml.="<![CDATA[".stripslashes($value)."]]>";
									}
									$xml.="</".$column.">";
									
								}
								$xml.="</item>";
							}
							$xml.= "</list>";
						}
					}else{
						$xml="<result type='ERROR'><![CDATA[Hata Oluştu :".$mysqli->errno." - ".$mysqli->error."]]></result>";
					}
				}else{
					$xml="<result type='ERROR'><![CDATA[Veritabanı Seçilmedi : ".$mysqli->error."]]></result>";					
				}
			}else{
				$xml="<result type='ERROR'><![CDATA[Veritabanı Seçilmedi]]></result>";
			}
			break;
	}
}
echo $xml;
echo "</content>";
?>