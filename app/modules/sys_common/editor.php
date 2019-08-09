<? 
defined("PASS") or die(label("Dosya Yok !"));
define("WEBIM_PAGE_TITLE", WEBIM_PAGE_CAPTION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=WEBIM_PAGE_TITLE?></title>
	 <meta http-equiv="Content-type" content="text/html; charset=<?=LANGUAGE_CHARSET?>" />
	<meta name="robots" content="noindex, nofollow" />
	<link href="objects/editor/fckeditor/sample.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="objects/editor/fckeditor/fckeditor.js"></script>
	<script language="JavaScript" type="text/javascript">
		var target = window.opener.document.getElementById('<?=getvalue("target","pageContent")?>')
		var sBasePath = "objects/editor/fckeditor/" ;
		var oFCKeditor = new FCKeditor(target.id) ;
		oFCKeditor.BasePath	= sBasePath ;
		oFCKeditor.SkinPath = oFCKeditor.BasePath + 'editor/skins/office2003/'
		oFCKeditor.Width	= 750 ;
		oFCKeditor.Height	= 500 ;
		
		var oEditor = null;
		window.onload=function(){
			oEditor = FCKeditorAPI.GetInstance(target.id) ;
		}
		function save(){
			var content 	= oEditor.GetXHTML(true);
			target.value 	= content;
			window.opener.document.getElementById('<?=getvalue("target","pageContent")?>Div').innerHTML = content;
			window.close();
		}
	</script>
</head>
<body>
	<form action="" method="post" onsubmit="return false;">
		<script type="text/javascript">
		<!--
		oFCKeditor.Value	= target.value ;
		oFCKeditor.Create() ;
		//-->
		</script>
		<br />	
	</form>
	<div align="right" style="width:750px;margin-top:10px;">	
			<input type="button" value="<?=label("Kaydet")?>" onclick="save();" />
			<input type="button" value="<?=label("Vazgeç")?>" />
	</div>
</body>
</html>
