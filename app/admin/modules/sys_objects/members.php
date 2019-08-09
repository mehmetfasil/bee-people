<?php
defined("PASS") or die("Dosya yok!");

//Genel Dosya
include_once(dirname(__FILE__).DS."lookups.php");

//Gruplar
$groups = array();

//Sorgusu
$query = "SELECT `g`.`id`, `g`.`name`, `g`.`fullname` "
. "FROM `sys_objects` `o` "
. "INNER JOIN `sys_groups` `g` ON (`o`.`id`=`g`.`id`) "
. "WHERE `o`.`active`='1'";
$select = new query($query, CONN);
while ($row = $select->fetchobject()) {
	$groups[$row->id] = "(".$row->name.") ".stripslashes($row->fullname);
}
?>
<script type="text/javascript">
//<![CDATA[
var f = new formAction();
f.url = url +'&act=';
f.y = <?=Y?>;
f.contents = {
 'dialog':false,
 'editing':false,
 'listing':false,
 'deleting':false
};
f.users = [];
f.getUsers = function (x){ 
 if (!this.users[getValue('gid',0)]){
  this.users[getValue('gid',0)] = [];
  var AJAX = new ajaxObject('get',this.url+'users&gid='+getValue('gid',0),null,{type:'MESSAGE',message:LANGUAGE['loading']});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('list');
    
    for (var i=0; i < xml.length; i++){
     f.users[getValue('gid',0)].push({
      id:xml.item(i).getAttribute('id'),
      level:xml.item(i).getAttribute('level'),
      name:xml.item(i).getAttribute('name'),
      fullname:xml.item(i).firstChild.data
     });
    }
    
    f.setUserList(0);
   }
  }
 }else{
  this.setUserList(x);
 }
};
f.setUserList = function (x){
 var target = getEl('usersDiv');
 var paging = getEl('paging1');
 var result = '';
 var filtered = [];
 var list = [];
 
 for (var i=0; i < this.users[getValue('gid',0)].length; i++){
  if (this.users[getValue('gid',0)][i]){
   if (getValue('level') > 0){
    if (this.users[getValue('gid',0)][i].level != getValue('level')){
     continue;
    }
   }
   if (getValue('filter1').length > 0){
    if ((this.users[getValue('gid',0)][i].name.indexOf(getValue('filter1')) < 0)
     && (this.users[getValue('gid',0)][i].fullname.indexOf(getValue('filter1')) < 0)){
      continue;
     }
   }
   filtered.push(this.users[getValue('gid',0)][i]);
  }
 }
 
 for (var i=0; i < filtered.length; i++){
  if ((Math.floor(i/this.y) == x) && (i%this.y < this.y)){
   list.push(filtered[i]);
  }
  if (list.length >= this.y){
   break;
  }
 }
 
 if (list.length > 0){
  //Sonuç
  result += '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
  for (var i=0; i < list.length; i++){
   result += '<tr>'
   + '<td class="gridRow" style="width:5%"><input type="checkbox" id="u'+ list[i].id +'" name="u'+ list[i].id +'" value="'+ list[i].id +'" /><\/td>'
   + '<td class="gridRow"><label for="u'+ list[i].id +'">('+ list[i].name +') '+ list[i].fullname +'<\/label><\/td>'
   + '<td class="gridRow" style="text-align:center; width:15%">';
   for (var z=0; z < parseInt(list[i].level); z++){
    result += '<img src="objects/icons/16x16/star.png" width="16" height="16" alt="" />';
   }
   result += '<\/td>'
   + '<\/tr>';
  }
  result += '<\/table>';
 }else{
  result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
 }
 
 target.innerHTML = result;
 
 var prv = x > 0 ? '<a href="javascript:void(0)" onclick="f.getUsers('+ (x-1) +')">&lt;&lt; '+ LANGUAGE['previous'] +'<\/a>' : '<span style="color:#aaaaaa">&lt;&lt; '+ LANGUAGE['previous'] +'<\/span>';
 var nxt = (x+1) < Math.ceil(filtered.length/this.y) ? '<a href="javascript:void(0)" onclick="f.getUsers('+ (x+1) +')">'+ LANGUAGE['next'] +' &gt;&gt;<\/a>' : '<span style="color:#aaaaaa">'+ LANGUAGE['next'] +' &gt;&gt;<\/span>';
 var tot = (x+1) +' / '+ Math.ceil(filtered.length/this.y);
 paging.innerHTML = '<div style="float:left"><label>'+ LANGUAGE['total'] +':<\/label> <b>'+ filtered.length +'<\/b><\/div><div>'+ prv +' &nbsp; '+ tot +' &nbsp; '+ nxt +'<\/div>';
 
 //Members
 this.getMembers(0);
};
f.members = [];
f.getMembers = function (x){
 if (!this.members[getValue('gid',0)]){
  this.members[getValue('gid',0)] = [];
  getEl('gid').disabled = true;
  
  var AJAX = new ajaxObject('get',this.url+'members&gid='+getValue('gid',0),null,{type:'MESSAGE',message:LANGUAGE['loading']});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('list');
    
    for (var i=0; i < xml.length; i++){
     f.members[getValue('gid',0)].push({
      id:xml.item(i).getAttribute('id'),
      level:xml.item(i).getAttribute('level'),
      name:xml.item(i).getAttribute('name'),
      fullname:xml.item(i).firstChild.data
     });
    }
    
    f.setMemberList(0);
    getEl('gid').disabled = false;
   }
  }
 }else{
  this.setMemberList(x);
 }
};
f.setMemberList = function (x){
 var target = getEl('membersDiv');
 var paging = getEl('paging2');
 var result = '';
 var filtered = [];
 var list = [];
 
 for (var i=0; i < this.members[getValue('gid',0)].length; i++){
  if (this.members[getValue('gid',0)][i]){
   if (getValue('filter2').length > 0){
    if ((this.members[getValue('gid',0)][i].name.indexOf(getValue('filter2')) < 0)
     && (this.members[getValue('gid',0)][i].fullname.indexOf(getValue('filter2')) < 0)){
     continue;
    }
   }
   filtered.push(this.members[getValue('gid',0)][i]);
  }
 }
 
 for (var i=0; i < filtered.length; i++){
  if ((Math.floor(i/this.y) == x) && (i%this.y < this.y)){
   list.push(filtered[i]);
  }
  if (list.length >= this.y){
   break;
  }
 }
 
 if (list.length > 0){
  //Sonuç
  result += '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
  for (var i=0; i < list.length; i++){
   result += '<tr>'
   + '<td class="gridRow" style="width:5%"><input type="checkbox" id="m'+ list[i].id +'" name="m'+ list[i].id +'" value="'+ list[i].id +'" /><\/td>'
   + '<td class="gridRow"><label for="m'+ list[i].id +'">('+ list[i].name +') '+ list[i].fullname +'<\/label><\/td>'
   + '<td class="gridRow" style="text-align:center; width:15%">';
   for (var z=0; z < parseInt(list[i].level); z++){
    result += '<img src="objects/icons/16x16/star.png" width="16" height="16" alt="" />';
   }
   result += '<\/td>'
   + '<\/tr>';
  }
  result += '<\/table>';
 }else{
  result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
 }
 
 target.innerHTML = result;
 
 var prv = x > 0 ? '<a href="javascript:void(0)" onclick="f.getMembers('+ (x-1) +')">&lt;&lt; '+ LANGUAGE['previous'] +'<\/a>' : '<span style="color:#aaaaaa">&lt;&lt; '+ LANGUAGE['previous'] +'<\/span>';
 var nxt = (x+1) < Math.ceil(filtered.length/this.y) ? '<a href="javascript:void(0)" onclick="f.getMembers('+ (x+1) +')">'+ LANGUAGE['next'] +' &gt;&gt;<\/a>' : '<span style="color:#aaaaaa">'+ LANGUAGE['next'] +' &gt;&gt;<\/span>';
 var tot = (x+1) +' / '+ Math.ceil(filtered.length/this.y);
 paging.innerHTML = '<div style="float:left"><label>'+ LANGUAGE['total'] +':<\/label> <b>'+ filtered.length +'<\/b><\/div><div>'+ prv +' &nbsp; '+ tot +' &nbsp; '+ nxt +'<\/div>';
};
f.add = function (){ 
 if (getValue('gid',0) > 0){  
  var source = getEl('usersDiv');  
  var els = source.getElementsByTagName('input');
  var arr = [];
  
  for (var i=0; i < els.length; i++){
   if ((els[i].type=='checkbox') && !els[i].disabled && els[i].checked){
    arr.push(els[i].value);
   }
  }
  
  if (arr.length > 0){   
   var params = 'id='+ getValue('gid',0) +'&ids='+ arr.join(',');
   var AJAX = new ajaxObject('post', this.url+'addmember', params, {type:'MESSAGE', message:LANGUAGE['saving']});
   AJAX.run();
   AJAX.onLoad = function (){
    if (AJAX.xml){
     var xml = AJAX.xml.getElementsByTagName('list');
     
     if (xml.length > 0){
      for (var i=0; i < xml.length; i++){
       for (var y=0; y < f.users[getValue('gid')].length; y++){
        if (f.users[getValue('gid')][y] && (f.users[getValue('gid')][y].id == xml.item(i).getAttribute('id'))){
         f.members[getValue('gid')].unshift({
          id:f.users[getValue('gid',0)][y].id,
          level:f.users[getValue('gid',0)][y].level,
          name:f.users[getValue('gid',0)][y].name,
          fullname:f.users[getValue('gid',0)][y].fullname
         });
         delete f.users[getValue('gid')][y];
         break;
        }
       }
      }
      f.getUsers(0);
     }else{
      messageDialog('<?=label("NO USER ADDED TO THE GROUP")?>', {type:'OK', icon:'caution.gif'});
     }
    }else{
     messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
    }
   }
  }else{
   messageDialog('<?=label("SELECT AT LEAST ONE")?>', {type:'OK', icon:'caution.gif'});
  }
 }else{
  messageDialog('<?=label("YOU MUST SELECT A GROUP NAME")?>', {type:'OK', icon:'warning.gif', functionOK:function(){getEl('gid').focus()}});
 }
};
f.remove = function (){
 if (getValue('gid',0) > 0){
  var source = getEl('membersDiv');
  var els = source.getElementsByTagName('input');
  var arr = [];
  
  for (var i=0; i < els.length; i++){
   if ((els[i].type=='checkbox') && !els[i].disabled && els[i].checked){
    arr.push(els[i].value);
   }
  }
  
  if (arr.length > 0){
   var params = 'id='+ getValue('gid',0) +'&ids='+ arr.join(',');
   var AJAX = new ajaxObject('post', this.url+'removemember', params, {type:'MESSAGE', message:LANGUAGE['deleting']});
   AJAX.run();
   AJAX.onLoad = function (){
    if (AJAX.xml){
     var xml = AJAX.xml.getElementsByTagName('list');
     
     if (xml.length > 0){
      for (var i=0; i < xml.length; i++){
       for (var y=0; y < f.members[getValue('gid')].length; y++){
        if (f.members[getValue('gid')][y] && (f.members[getValue('gid')][y].id == xml.item(i).getAttribute('id'))){
         f.users[getValue('gid')].unshift({
          id:f.members[getValue('gid',0)][y].id,
          level:f.members[getValue('gid',0)][y].level,
          name:f.members[getValue('gid',0)][y].name,
          fullname:f.members[getValue('gid',0)][y].fullname
         });
         f.members[getValue('gid')][y] = null;
         delete f.members[getValue('gid')][y];
         break;
        }
       }
      }
      f.getUsers(0);
     }else{
      messageDialog('<?=label("NO USER ADDED TO THE GROUP")?>', {type:'OK', icon:'caution.gif'});
     }
    }else{
     messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
    }
   }
  }else{
   messageDialog('<?=label("SELECT AT LEAST ONE")?>', {type:'OK', icon:'caution.gif'});
  }
 }else{
  messageDialog('<?=label("YOU MUST SELECT A GROUP NAME")?>', {type:'OK', icon:'warning.gif', functionOK:function(){getEl('gid').focus()}});
 }
};
f.selectUnselect = function (targetID, act){
 var target = getEl(targetID+'Div');
 var els = target.getElementsByTagName('input');
  
 for (var i=0; i < els.length; i++){
  if ((els[i].type=='checkbox') && !els[i].disabled){
   els[i].checked = (act=='select' ? true : false);
  }
 }
};

addListener(window,'load',function(){f.getUsers(0)});
//]]>
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="width:48%; text-align:center; padding:5px" valign="top" class="gridTitle">
      <img src="objects/icons/16x16/user.png" width="16" height="16" alt="" />
      <?=label("USER LIST")?>
    </td>
    <td style="width:4%" valign="top" class="gridTitle">&nbsp;</td>
    <td style="width:48%; text-align:center; padding:5px" valign="top" class="gridTitle">
      <img src="objects/icons/16x16/group.png" width="16" height="16" alt="" />
      <?=label("MEMBER LIST")?>
    </td>
  </tr>
  <tr>
    <td class="gridRow">
      <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td class="gridLeft" style="width:25%"><label><?=label("USER LEVEL")?></label></td>
          <td class="gridRight">
            <?=formElement("select", "level", (array("&lt;&lt; ".label("ALL")." &gt;&gt;") + $levels), "", "", "style=\"width:300px\" onchange=\"f.getUsers(0)\"")?>
          </td>
        </tr>
        <tr>
          <td class="gridLeft"><label><?=label("FILTER")?></label></td>
          <td class="gridRight">
            <?=formElement("text", "filter1", "", "", "", "style=\"width:300px\" maxlength=\"100\" onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.getUsers(0)}\"")?>
          </td>
        </tr>
      </table>
    </td>
    <td rowspan="2" class="gridRow" style="text-align:center">
      <?=formElement("button", "addBtn", "", "&gt;&gt;", "", "class=\"button\" style=\"width:30px;\" onclick=\"f.add()\"")?>
      <br/>
      <br/>
      <br/>
      <?=formElement("button", "removeBtn", "", "&lt;&lt;", "", "class=\"button\" style=\"width:30px;\" onclick=\"f.remove()\"")?>
    </td>
    <td class="gridRow">
      <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td class="gridLeft"><label><?=label("GROUP NAME")?></label></td>
          <td class="gridRight">
            <?=formElement("select", "gid", $groups, "", "disabled", "style=\"width:300px\" onchange=\"f.getUsers(0)\"")?>
          </td>
        </tr>
        <tr>
          <td class="gridLeft" style="width:25%"><label><?=label("FILTER")?></label></td>
          <td class="gridRight">
            <?=formElement("text", "filter2", "", "", "", "style=\"width:300px\" maxlength=\"100\" onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.getUsers(0)}\"")?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class="gridRow">
      <div style="padding:5px">
        <a class="on" href="javascript:void(0)" onclick="f.selectUnselect('users','select')"><?=label("SELECT ALL")?></a>
        &nbsp;&nbsp;
        <a class="off" href="javascript:void(0)" onclick="f.selectUnselect('users','unselect')"><?=label("UNSELECT ALL")?></a>
      </div>
      <fieldset>
        <div id="usersDiv" class="list" style="height:400px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
      <div id="paging1" style="padding:5px; text-align:right"><div style="float:left"><label><?=label("TOTAL")?>:</label> <b>0</b></div><div><span style="color:#aaaaaa">&lt;&lt; <?=label("PREVIOUS")?></span> 1 / 1 <span style="color:#aaaaaa"><?=label("NEXT")?> &gt;&gt;</span></div></div>
    </td>
    <td class="gridRow">
      <div style="padding:5px">
        <a class="on" href="javascript:void(0)" onclick="f.selectUnselect('members','select')"><?=label("SELECT ALL")?></a>
        &nbsp;&nbsp;
        <a class="off" href="javascript:void(0)" onclick="f.selectUnselect('members','unselect')"><?=label("UNSELECT ALL")?></a>
      </div>
      <fieldset>
        <div id="membersDiv" class="list" style="height:400px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
      <div id="paging2" style="padding:5px; text-align:right"><div style="float:left"><label><?=label("TOTAL")?>:</label> <b>0</b></div><div><span style="color:#aaaaaa">&lt;&lt; <?=label("PREVIOUS")?></span> 1 / 1 <span style="color:#aaaaaa"><?=label("NEXT")?> &gt;&gt;</span></div></div>
    </td>
  </tr>
</table>