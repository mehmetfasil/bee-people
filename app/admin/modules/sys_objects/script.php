<?php
defined("PASS") or die("Dosya yok!");
?>
<script type="text/javascript">
//<![CDATA[
var f = new formAction();
f.url = url+'&act=';
f.contents = {
 'dialog':false,
 'editing':false,
 'listing':false,
 'deleting':false
};
f.form = 'mainform';
f.create = function (){
 if (getValue('script').length > 0){
  var AJAX = new ajaxObject('post', this.url+'script', getParams(this.form), {type:'MESSAGE', message:LANGUAGE['processing']});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('result');
    var line = AJAX.xml.getElementsByTagName('list');
    
    if (xml.length > 0){
     messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'info.gif'});
     
     var result = '';
     if (line.length > 0){
      for (var i=0; i < line.length; i++){
       result += line.item(i).firstChild.data +'\n';
      }
     }
     setParam('script',result);
    }else{
     messageDialog(LANGUAGE['noReturn'], {type:'OK', icon:'warning.gif'});
    }
   }else{
    messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
   }
  }
 }else{
  messageDialog('<?=label("DO NOT LEAVE EMPTY THE FIELD")?>', {type:'OK', icon:'caution.gif', functionOK:function(){focusOn('script')}});
 }
};
//]]>
</script>

<form id="mainform" method="post" onsubmit="return false" action="#">
<fieldset>
<div class="question">
  <label for="script"><?=label("USER NAME").", ".label("USER FULLNAME")." [, ".label("USER PASS").", ".label("USER EMAIL").", ".label("USER DETAIL")."]"?></label>
</div>
<div>
<?=formElement("textarea", "script", "", "", "", "style=\"width:100%; height:400px\"")?>
</div>
</fieldset>
<div class="buttonBar">
<?=formButton("button", "createBtn", label("CREATE"), "", "apply.png", "style=\"width:120px;\" onclick=\"f.create();\"");?>
</div>
</form>