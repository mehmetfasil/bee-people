<?php
defined("PASS") or die("Dosya yok!");
?>
<script type="text/javascript">
//<![CDATA[
var f = new formAction();
f.url = url +'&act=';
f.setGalleryList = function (){
 var target = getEl('galleryList');
 var AJAX = new ajaxObject('get', this.url+'galleries', null, {type:'LIST', message:LANGUAGE['loading'], target:target});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('list');
   var result = '';
   
   if (xml.length > 0){
    result += '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
    
    for (var i=0; i < xml.length; i++){
     result += '<tr>'
     + '<td class="gridRow" onmouseover="overRow(this,\'gridRowOver\')" onmouseout="overRow(this,\'gridRow\')" onclick="f.setPictureList('+ xml.item(i).getAttribute('id') +')">'+ xml.item(i).firstChild.data +'<\/td>'
     + '<\/tr>';
    }
    
    result += '<\/table>';
   }else{
    result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
   }
   
   target.innerHTML = result;
  }else{
   messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
  }
 }
};
f.setPictureList = function (){
 
};

addListener(window,'load',function(){
 f.setGalleryList();
});
//]]>
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="width:300px" valign="top">
      <fieldset>
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td class="gridLeft" style="width:20%"><label for="listKeyword"><?=label("FILTER")?></label></td>
            <td class="gridRight">
              <?=formElement("text", "listKeyword", "", "", "", "onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.setGalleryList()}\" class=\"searchInput\" style=\"width:180px\"")?>
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <div class="list" id="galleryList" style="height:450px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
    </td>
    <td valign="top">
      <fieldset>
        <div class="list" id="pictureList" style="height:500px"><div class="warning"><?=label("PICK A GALLERY")?></div></div>
      </fieldset>
      <div id="buttonBar" class="buttonBar" style="display:none">
        <?=formButton("button", "uploadBtn", label("UPLOAD IMAGE"), "", "upload.png", "onclick=\"f.showUpload()\"")?>
      </div>
    </td>
  </tr>
</table>