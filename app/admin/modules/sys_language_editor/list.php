<?php
defined("PASS") or die("Dosya yok!");

$charsets = array(
 "utf-8" => "UTF-8 (Unicode)",
 "iso-8859-9" => "ISO-8859-9 (Turkish)",
 "iso-8859-1" => "ISO-8859-1 (Western European)",
 "iso-8859-6" => "ISO-8859-6 (Arabic)",
 "iso-8859-4" => "ISO-8859-4 (Baltic)",
 "iso-8859-5" => "ISO-8859-5 (Cyrilic)",
 "iso-8859-7" => "ISO-8859-7 (Greek)",
 "iso-8859-8" => "ISO-8859-8 (Hebrew)",
 "iso-8859-3" => "ISO-8859-3 (Maltese)",
 "euc-cn" => "euc-cn (Chinese Simplified)",
 "euc-kr" => "euc-kr (Korean)"
);

$directions = array(
 "ltr" => label("LEFT TO RIGHT"),
 "rtl" => label("RIGHT TO LEFT")
);

$head = array();
$list = array();

if (getvalue("type",array("php","js")) == "php") {
 $path = CONF_DOCUMENT_ROOT."system".DS."languages".DS.getvalue("language", DEFAULT_LANGUAGE).DS;
 $filename = $path."language";
 
 if (!file_exists($path)) {
 	@mkdir($path, 0777);
 }
 
 if (!file_exists($filename)) {
 	@touch($filename);
 	$row = "[ABBR=xx;NAME=Unknown;ENG_NAME=Unknown;CHARSET=utf-8;DIRECTION=ltr;]";
 	$fp = @fopen($filename, "w+");
 	@fwrite($fp, $row);
 	@fclose($fp);
 }
 
 //Okuyalım
 $contents = file_get_contents($filename);
 $raw = explode("[\r\n]", $contents);
 
 foreach ($raw as $row){
  if (strlen($row) > 0){
   if (preg_match("/^\[(.*?)\]$/", $row, $match)) {
   	$raw2 = explode(";", $match[1]);
   	foreach ($raw2 as $row2){
   	 if (strlen($row2) > 0){
   	  $values = explode("=", $row2);
   	  list($key, $value) = $values;
   	  $head[$key] = $value;
   	 }
   	}
   }elseif (preg_match("/^(.*?)[\s]*?\:\=[\s]*?(.*?)$/", $row, $match)){
    $list[$match[1]] = $match[2];
   }
  }
 }
}else{
 $path = CONF_DOCUMENT_ROOT."objects".DS."js".DS."languages".DS.getvalue("language", DEFAULT_LANGUAGE).DS;
 $filename = $path."language.js";
 
 if (!file_exists($path)) {
 	@mkdir($path, 0777);
 }
 
 if (!file_exists($filename)) {
 	@touch($filename);
 }
 
 //Okuyalım
 $contents = file_get_contents($filename);
 $raw = explode("[\r\n]", $contents);
 
 foreach ($raw as $row){
  if (strlen($row) > 0){
   if (preg_match("/^LANGUAGE\[\'(.*?)\'\][\s]*?\=[\s]*?\'(.*)\'[\;]*?$/", $row, $match)) {
   	$list[$match[1]] = $match[2];
   }
  }
 }
}
?>
<script type="text/javascript">
//<![CDATA[
var f = new formAction();
f.url = url +'&act=';
f.form = 'mainform';
f.contents = {
 'dialog':false,
 'editing':false,
 'listing':false,
 'deleting':false
};
f.resize = function (){
 getEl('list').style.height = (getWinH()-350)+'px'; 
};
f.change = function (){
 var u = '<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION."&pid=".PID?>';
 u += '&language='+ getValue('language')
 u += '&type='+ getValue('type');
 window.location.href = u;
};
f.remove = function (obj){
 var tr = obj.parentNode.parentNode;
 if (tr.tagName.toLowerCase() == 'tr'){
  tr.parentNode.removeChild(tr);
 }else{
  messageDialog('<?=label("ELEMENT NOT FOUND")?>', {type:'OK', icon:'warning.gif'});
 }
};
f.add = function (){
 var buttons = {
  type:'SAVECANCEL',
  labelSAVE:'<?=label("ADD")?>',
  functionSAVE:function(){
   if ((getValue('key').length > 0) && (getValue('value').length > 0)){    
    var target = getEl('list');
    var els = target.getElementsByTagName('tr');
    
    if (els.length < 1){
     var tbl = Layer.createElement('table',target);
     tbl.width = '100%';
     tbl.cellPadding = '5';
     tbl.cellSpacing = '0';
     tbl.border = '0';
     var tbd = Layer.createElement('tbody',tbl);
    }else{
     tbd = els[els.length-1].parentNode;
    }
    
    if (tbd){
     var tr = Layer.createElement('tr', tbd);
     var td1 = Layer.createElement('td', tr);
     td1.className = 'gridLeft';
     td1.style.width = '40%';
     td1.innerHTML = '<label for="value'+ els.length +'">'+ getValue('key') +'<\/label>';
     var td2 = Layer.createElement('td', tr);
     td2.className = 'gridRight';
     td2.innerHTML = '<input type="hidden" id="key'+ els.length +'" name="key'+ els.length +'" value="'+ getValue('key') +'" \/>'
     + '<input type="text" id="value'+ els.length +'" name="value'+ els.length +'" value="'+ getValue('value') +'" onfocus="this.select()" style="width:450px" \/> '
     + '<a href="javascript:void(0)" onclick="f.remove(this)"><img src="objects/icons/16x16/drop.png" width="16" height="16" alt="" \/><\/a>';
     
     layer('hide','dialog');
     
     target.scrollTop = target.scrollHeight;
    }else{
     messageDialog('<?=label("ELEMENT NOT FOUND")?>', {type:'OK', icon:'warning.gif'});
    }
   }else{
    messageDialog('<?=label("FILL DEFINITION AND VALUE")?>', {type:'OK', icon:'warning.gif'});
   }
  },
  functionCANCEL:function(){
   layer('hide','dialog');
  }
 };
 layer('show', 'dialog', 500, 300, '<?=label("NEW DEFINITION")?>', buttons);
};
f.del = function (){
 messageDialog('<?=label("ARE YOU SURE YOU WANT TO DELETE")?>', {type:'YESNO', icon:'caution.gif', functionYES:function(){
  var AJAX = new ajaxObject('post', f.url+'delete', 'language='+ getValue('language'), {type:'MESSAGE', message:LANGUAGE['deleting']});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('result');
    if (xml.length > 0){
     if (xml.item(0).getAttribute('status') == 'OK'){
      messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'info.gif', functionOK:function(){
       setParam('language','<?=DEFAULT_LANGUAGE?>');
       f.change();
      }});
     }else{
      messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'caution.gif'});
     }
    }else{     
     messageDialog(LANGUAGE['noReturn'], {type:'OK', icon:'warning.gif'});
    }
   }else{
    messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
   }
  }
 }});
};
f.duplicate = function (){
 var buttons = {
  type:'SAVECANCEL',
  functionSAVE:function(){
   if (getValue('new_abbr').length == 2){
    if (getValue('new_name').length > 0){
     var sourceLang = getValue('language');
     var AJAX = new ajaxObject('post', f.url+'duplicate', 'language='+sourceLang+'&'+getParams('dialog2form'), {type:'DIALOG', message:LANGUAGE['saving'], target:'dialog2'});
     AJAX.run();
     AJAX.onLoad = function (){
      if (AJAX.xml){
       var xml = AJAX.xml.getElementsByTagName('result');
       if (xml.length > 0){
        if (xml.item(0).getAttribute('status') == 'OK'){
         messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'info.gif', functionOK:function(){
          listOptions(getEl('language'), getValue('new_name','unknown'), getValue('new_abbr'));
          setParam('language',getValue('new_abbr'));
          layer('hide','dialog2');
          f.change();
         }});
        }else{
         messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'caution.gif'});
        }
       }else{     
        messageDialog(LANGUAGE['noReturn'], {type:'OK', icon:'warning.gif'});
       }
      }else{
       messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
      }
     }
    }else{
     messageDialog('YOU MUST SPECIFY NEW LANGUAGE NAME');
    }
   }else{
    messageDialog('YOU MUST SPECIFY NEW LANGUAGE ABBREVATION');
   }
  },
  functionCANCEL:function(){
   layer('hide','dialog2');
  }
 };
 layer('show', 'dialog2', 500, 300, '<?=label("DUPLICATE")?>', buttons);
};

addListener(window, 'load', function(){
 f.resize();
 getEl('list').scrollTop = getEl('list').scrollHeight;
 getEl('addBtn').disabled = false;
 getEl('saveBtn').disabled = false;
 getEl('delBtn').disabled = false;
});
addListener(window, 'resize', f.resize);
//]]>
</script>

<form id="mainform" method="post" action="#">
<fieldset>
<div>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:15%"><label for="language"><?=label("CURRENT LANGUAGE FILE")?></label></td>
    <td class="gridRight" style="width:35%">
      <?=formElement("select", "language", getLanguages(), getvalue("language",DEFAULT_LANGUAGE), "", "style=\"width:150px\" onchange=\"f.change()\"")?>
      <?=formElement("button", "duplicateBtn", "", label("DUPLICATE"), "", "onclick=\"f.duplicate()\" class=\"button\"")?>
    </td>
    <td class="gridLeft" style="width:15%"><label for="php"><?=label("FILE TYPE")?></label></td>
    <td class="gridRight" style="width:35%">
      <?=formElement("radio", "type", "php", getvalue("type",array("php","js")), "", "onclick=\"f.change()\"", "php")?> <label for="php">PHP</label>
      <?=formElement("radio", "type", "js", getvalue("type",array("php","js")), "", "onclick=\"f.change()\"", "js")?> <label for="js">JAVASCRIPT</label>
    </td>
  </tr>
</table>
</div>
</fieldset>

<?
if (getvalue("type",array("php","js")) == "php") {
?>
<fieldset>
<div>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:15%"><label for="ABBR"><?=label("LANGUAGE ABBREVATION")?></label></td>
    <td class="gridRight" style="width:35%">
      <?=formElement("text", "ABBR", "", @$head["ABBR"], "", "style=\"width:150px\" maxlength=\"2\"")?>
    </td>
    <td class="gridLeft" style="width:15%"><label for="NAME"><?=label("LANGUAGE NAME")?></label></td>
    <td class="gridRight" style="width:35%">
      <?=formElement("text", "NAME", "", @$head["NAME"], "", "style=\"width:150px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="ENG_NAME"><?=label("LANGUAGE ENGLISH NAME")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "ENG_NAME", "", @$head["ENG_NAME"], "", "style=\"width:150px\"")?>
    </td>
    <td class="gridLeft"><label for="CHARSET"><?=label("CHARSET")." / ".label("LETTER DIRECTION")?></label></td>
    <td class="gridRight">
      <?=formElement("select", "CHARSET", $charsets, @$head["CHARSET"], "", "")?>
      <?=formElement("select", "DIRECTION", $directions, @$head["LTR"], "", "")?>
    </td>
  </tr>
</table>
</div>
</fieldset>
<?
}
?>

<fieldset>
<div id="list" style="height:300px; overflow-x:hidden; overflow-y:auto;">
<?
if (count($list) > 0) {
?>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tbody>
    <?
    $i=1;
    foreach ($list as $key=>$value){
    ?>
    <tr>
      <td class="gridLeft" style="width:40%"><label for="value<?=$i?>"><?=$key;?></label></td>
      <td class="gridRight">
        <?=formElement("hidden", "key".$i, "", $key, "", "")?>
        <?=formElement("text", "value".$i, "", $value, "", "style=\"width:450px;\" onfocus=\"this.select()\"")?>
        <a href="javascript:void(0)" onclick="f.remove(this)"><img src="objects/icons/16x16/drop.png" width="16" height="16" alt="" /></a>
      </td>
    </tr>
    <?
     $i++;
    }
    ?>
  </tbody>
</table>
<?
}
?>
</div>
</fieldset>
</form>

<div class="buttonBar">
<?=formButton("button", "addBtn", label("ADD DEFINITION"), "disabled", "new.png", "onclick=\"f.add()\"")?>
<?=formButton("button", "saveBtn", label("WRITE FILE"), "disabled", "save.png", "onclick=\"f.save()\"")?>
<?=formButton("button", "delBtn", label("DELETE LANGUAGE"), "disabled", "drop.png", "onclick=\"f.del()\"")?>
</div>

<div id="dialog" style="display:none">
<form id="dialogform" method="post" action="#">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:25%"><label for="key"><?=label("DEFINITION")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "key", "", "", "", "style=\"width:300px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="value"><?=label("VALUE")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "value", "", "", "", "style=\"width:300px\"")?>
    </td>
  </tr>
</table>
</form>
</div>

<div id="dialog2" style="display:none">
<form id="dialog2form" method="post" action="#">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:25%"><label for="new_abbr"><?=label("LANGUAGE ABBREVATION")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "new_abbr", "", "", "", "style=\"width:300px\" maxlength=\"2\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="new_name"><?=label("LANGUAGE NAME")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "new_name", "", "", "", "style=\"width:300px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="new_eng_name"><?=label("LANGUAGE ENGLISH NAME")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "new_eng_name", "", "", "", "style=\"width:300px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="new_charset"><?=label("CHARSET")?></label></td>
    <td class="gridRight">
      <?=formElement("select", "new_charset", $charsets, "", "", "style=\"width:200px\"")?>
      <?=formElement("select", "new_direction", $directions, "", "", "style=\"width:100px\"")?>
    </td>
  </tr>
</table>
</form>
</div>