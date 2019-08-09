<?

//menu ismini cekelim
function getMenu($menu){
	$query = "SELECT m.id,m.name,p.title FROM sys_menus AS m LEFT JOIN sys_pages AS p ON(p.mid=m.id) WHERE m.name='$menu' ";
	$select = new query($query);
	$row=$select->fetchobject();
	$menu=$row->title;
	echo "<div class='menuName'>";
	echo "<img src='objects/icons/24x24/diagram.png'>".$menu;
	echo "</div>";
}

//tablolar
$table_app_users = " app_users ";
$table_app_questions = " app_questions ";
$table_app_questions_answers = " app_questions_answers ";


$cevap_siklari = array("1"=>"A","2"=>"B",3=>"C","4"=>"D","5"=>"E");

//img src
$imgsrc = "objects/icons/16x16/";
?>