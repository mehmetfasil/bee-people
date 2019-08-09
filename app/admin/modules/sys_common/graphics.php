<?php
defined("PASS") or die("Dosya yok!");

if (STEP == "data") {
 //Grafik Dosyası
 require_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.graphic.data.php");
 
 $data = array(0,0,0,0,0,0,0);
 
 //Gün
 $g = 6;
 
 //Sorgusu
 $query = "SELECT DATE_FORMAT(`time`, '%Y%m%d') AS `tmptime`, COUNT(*) AS `total` "
 . "FROM `sys_sessions` "
 . "WHERE `time` > DATE_SUB(NOW(), INTERVAL 6 DAY) "
 . "GROUP BY `tmptime` "
 . "ORDER BY `tmptime` DESC";
 $select = new query($query);
 while ($row = $select->fetchobject()) {
 	$data[$g]	= (int) $row->total;
 	$g--;
 }
 
 $chart = new open_flash_chart();

 $title = new title(label("LAST WEEK STATS"));
 $title->set_style("{font-weight:bold; font-size:18px; background-color:#dfdaa7}");

 $line_dot = new line_dot();
 $line_dot->set_values($data);

 $chart->set_title($title);
 $chart->add_element($line_dot);
 
 $x = new x_axis();
 $x->set_labels_from_array(array("1","2","3","4","5","6","7"));

 $y = new y_axis();
 $y->set_stroke(1);
 $y->set_colour('#000000');
 $y->set_tick_length(7);
 $y->set_grid_colour('#a2acba');
 $y->set_range(0, (max($data)+ ceil(array_sum($data) / 7)), ceil(max($data) / 2));

 $chart->set_x_axis($x);
 $chart->set_y_axis($y);

 echo $chart->toString();
}else{
 define("WEBIM_PAGE_TITLE", WEBIM_PAGE_CAPTION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANG?>" lang="<?=LANG?>">
<head>
<title><?=WEBIM_PAGE_TITLE?></title>
<meta http-equiv="Content-type" content="text/html; charset=<?=LANGUAGE_CHARSET?>" />
<meta name="author" content="<?=AUTHOR;?>" />

<style type="text/css">
<!--
body {
 margin:0;
 text-align:center;
}
-->
</style>

<script type="text/javascript" src="objects/js/swfobject.js"></script>
<script type="text/javascript">
//<![CDATA[
swfobject.embedSWF(
'objects/assets/_system/chart.swf', 'graph', 370, 250, '9.0.0', 'objects/assets/_system/expressInstall.swf',
{
 'data-file':'<?=urlencode(CONF_MAIN_PAGE."?".ADMIN_EXTENSION."&pid=".PID."&step=data");?>',
 'loading': '<?=label("LOADING")?>'
}
);
//]]>
</script>

</head>
<body>
<div id="graph"></div>
</body>
</html>
<?
}
?>