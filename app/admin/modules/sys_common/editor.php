<?php
defined("PASS") or die("Dosya yok!");

define("WEBIM_PAGE_TITLE", WEBIM_PAGE_CAPTION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANG?>" lang="<?=LANG?>">
<head>
<title><?=WEBIM_PAGE_TITLE?></title>
<meta http-equiv="Content-type" content="text/html; charset=<?=LANGUAGE_CHARSET?>" />
<link type="text/css" href="<?=ADMIN_FOLDER;?>/templates/_default/common.css" rel="stylesheet" />
<script type="text/javascript" src='objects/editor/scripts/innovaeditor.js'></script>
<script type="text/javascript">
<!--
var target = window.opener.getEl('<?=getvalue("target");?>');

function getEl (id){
 return document.getElementById(id);
}

function send (){
	if (target){
		//Hidden
		target.value = getEl('txtContent').value;

		//Yazdıralım
		window.opener.getEl('<?=getvalue("target");?>Div').innerHTML = target.value;
		window.close();
	}else{
		messageDialog('<?=label("SOURCE ID NOT FOUND")?>', {type:'OK', icon:'warning.gif', functionOK:'window.close()'});
	}
}
//-->
</script>
</head>
<body>
<form id="editor" method="post" onsubmit="send(); return false;" action="#">
<div style="display:none"><?=formElement("textarea", "txtContent", "", "", "", "")?></div>
<div style="height:500px;">
<script type="text/javascript">
<!--
var oEdit1 = new InnovaEditor("oEdit1");

/***************************************************
  SETTING EDITOR DIMENSION (WIDTH x HEIGHT)
***************************************************/

oEdit1.width='100%';//You can also use %, for example: oEdit1.width="100%"
oEdit1.height= (navigator.appName == "Microsoft Internet Explorer" ? 490 : 560);

/***************************************************
  SHOWING DISABLED BUTTONS
***************************************************/

oEdit1.btnFlash=true;
oEdit1.btnMedia=true;
oEdit1.btnLTR=true;
oEdit1.btnRTL=true;
oEdit1.btnStrikethrough=true;
oEdit1.btnSuperscript=true;
oEdit1.btnSubscript=true;
oEdit1.btnClearAll=true;
oEdit1.btnStyles=true; //Show "Styles/Style Selection" button

/***************************************************
  APPLYING STYLESHEET
  (Using external css file)
***************************************************/

oEdit1.css="<?=ADMIN_FOLDER;?>/templates/_default/common.css"; //Specify external css file here

/***************************************************
  APPLYING STYLESHEET
  (Using predefined style rules)
***************************************************/
/*
oEdit1.arrStyle = [["BODY",false,"","font-family:Verdana,Arial,Helvetica;font-size:x-small;"],
      [".ScreenText",true,"Screen Text","font-family:Tahoma;"],
      [".ImportantWords",true,"Important Words","font-weight:bold;"],
      [".Highlight",true,"Highlight","font-family:Arial;color:red;"]];

If you'd like to set the default writing to "Right to Left", you can use:

oEdit1.arrStyle = [["BODY",false,"","direction:rtl;unicode-bidi:bidi-override;"]];
*/


/***************************************************
  ENABLE ASSET MANAGER ADD-ON
***************************************************/

if (navigator.appName == 'Microsoft Internet Explorer'){
 oEdit1.cmdAssetManager = "modalDialogShow('../assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
}else{
 oEdit1.cmdAssetManager = "modalDialogShow('../../assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
}
//Use relative to root path (starts with "/")

/***************************************************
  ADDING YOUR CUSTOM LINK LOOKUP
***************************************************/

//oEdit1.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.

/***************************************************
  ADDING YOUR CUSTOM CONTENT LOOKUP
***************************************************/

//oEdit1.cmdCustomObject = "modelessDialogShow('objects/editor/scripts/smileys.htm',365,270)"; //Command to open your custom content lookup page.

/***************************************************
  USING CUSTOM TAG INSERTION FEATURE
***************************************************/

oEdit1.arrCustomTag=[["User Name","{%user_name%}"],
    ["Full Name","{%full_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

/***************************************************
  SETTING COLOR PICKER's CUSTOM COLOR SELECTION
***************************************************/

oEdit1.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9"];//predefined custom colors

/***************************************************
  SETTING EDITING MODE

  Possible values:
    - "HTMLBody" (default)
    - "XHTMLBody"
    - "HTML"
    - "XHTML"
***************************************************/

oEdit1.mode="XHTMLBody";
getEl('txtContent').value = target.value;
oEdit1.REPLACE("txtContent");
//-->
</script>
</div>
<div style="position:fixed; bottom:0; left:0; width:100%; text-align:right; margin:10px">
<?=formButton("submit", "submitBtn", label("OK"), "", "apply.png", "")?>
<?=formButton("button", "cancelBtn", label("CANCEL"), "", "exit.png", "onclick=\"window.close()\"")?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
</form>
</body>
</html>
