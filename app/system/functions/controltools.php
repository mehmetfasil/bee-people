<? 
function checkIpStatus($ip){
	$query = "SELECT ip,status FROM ip_table WHERE ip='".$ip."'";
	$select = new query($query);
	if($select->numrows()>0){		
		$row=$select->fetchobject();
		if($row->status=="deny"){
			exit("<div style='padding:10px;color:#FF0000;font-size:30px;font-family:Arial,sans-serif;font-weight:bold;'>Dosya Yok !</div>");
		}
	}
}
?>