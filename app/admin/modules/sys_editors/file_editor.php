<?php
defined("PASS") or die("Dosya yok!");

$text = "";
?>
<script type="text/javascript">
//<![CDATA[
function showDialog (){
 var buttons = {
  type:'OK',
  labelOK:LANGUAGE['cancel'],
  functionOK:function(){
   layer('hide','dialog');
  }
 };
 getEl('pathSpan').innerHTML = '';
 layer('show', 'dialog', 400, 300, '<?=label("PICK A FILE")?>', buttons);
 setFiles('/'); 
}

function setFiles (path){
 var target = getEl('fileList');
 target.innerHTML = '';
 var result = '';
 var AJAX = new ajaxObject('get', url+'&act=files', 'path='+path, {type:'DIALOG',message:LANGUAGE['loading'],target:'dialog'});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('list');
   if (xml.length > 0){
    result += '<table width="100%" border="0" cellpadding="2" cellspacing="0">\n'
    for (var i=0; i < xml.length; i++){
     result += '<tr>\n'
     + '<td style="width:20px"><img src="objects/icons/tree/'+ ((xml.item(i).getAttribute('type') == 'folder') ? 'close.gif' : 'item.gif') +'" alt=""><\/td>\n'
     + '<td><a href="javascript:void(0)" onclick="setPath(\''+ xml.item(i).getAttribute('type') +'\', \''+ xml.item(i).getAttribute('path') +'\')">'+ xml.item(i).firstChild.data +'<\/a><\/td>\n'
     + '<\/tr>\n';
    }
    result += '<\/table>\n';
   }else{
    result += '<div class="warning"><?=label("EMPTY FOLDER")?><\/div>';
   }
  }else{
   result += '<div class="warning">'+ LANGUAGE['unknownError'] +'<\/div>';
  }
  target.innerHTML = result;
 }
}

function setPath (type, path){
 if (type == 'folder'){
  var target = getEl('pathSpan');
  var tmp = '';
  var raw = path.split('\/');
  var raw2 = '';
  for (var i=0; i < raw.length; i++){
   if (raw[i].length > 0){
    raw2 += '/'+ raw[i];
    tmp += ' / <a href="javascript:void(0)" onclick="setPath(\'folder\', \''+ raw2 +'/\')">'+ raw[i] +'<\/a>';
   }
  }
  target.innerHTML = tmp;
  setFiles(path);
 }else{
  openFile(path);
  layer('hide','dialog');
 }
}

function openFile (path){
 getEl('filenameDiv').innerHTML = path;
}

//]]>
</script>
<fieldset>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:150px"><label><?=label("FILE NAME")?></label></td>
    <td class="gridRight">
      <?=label("MAIN FOLDER")?><span id="filenameDiv">/</span>
    </td>
    <td class="gridRowOver" style="width:200px; text-align:center">
      <a class="new" href="javascript:void(0)"><?=label("NEW FILE")?></a>
      &nbsp;&nbsp;
      <a class="open" href="javascript:void(0)" onclick="showDialog()"><?=label("OPEN FILE")?></a>
    </td>
  </tr>
</table>
</fieldset>

<form id="mainform" method="post" action="#">
<fieldset>
<?=formElement("hidden", "filename", "", "", "", "")?>
<?=formElement("textarea", "text", "", $text, "", "style=\"width:100%; height:500px\"")?>
</fieldset>
</form>

<div class="buttonBar">
<?=formButton("button", "saveBtn", label("SAVE"), "", "save.png", "onclick=\"f.save()\"")?>
</div>

<div id="dialog" style="display:none">
<div class="gridRowOver" style="line-height:25px; overflow:hidden;"><a href="javascript:void(0)" onclick="setPath('folder', '')">.root</a> <span id="pathSpan"></span></div>
<div id="fileList" style="height:250px; overflow:auto"></div>
</div>