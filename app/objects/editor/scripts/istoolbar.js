/***********************************************************
InnovaStudio WYSIWYG Editor 3.0
© 2007, InnovaStudio (www.innovastudio.com). All rights reserved.
************************************************************/
var UA = navigator.userAgent.toLowerCase();
var isIE = (UA.indexOf('msie') >= 0) ? true : false;
var isNS = (UA.indexOf('mozilla') >= 0) ? true : false;

function ISToolbarManager(id) {
  this.id=id;
  this.btns=new Object();
  this.tbars=new Object();
  this.groups=new Object();
  this.tabCtl=null;
  
  this.createToolbar=function(id){
    var tb=new ISToolbar(id);
    tb.mgr=this;
    this.tbars[id]=tb;
    return tb;
  }
  
  this.createTbGroup=function(id){
    var gr=new ISToolbarGroup(id);
    gr.mgr=this;
    this.groups[id]=gr;
    return gr;
  }
  
  this.createTbTab=function(id) {
    var tab=new ISTabCtl(id);
    tab.mgr=this;
    this.tabCtl=tab;
    return tab;
  }
  
  this.render=function() {
    if(this.tabCtl) return this.tabCtl.render();
    var ret="";
    for(var i in this.groups) { ret += this.groups[i].render(); }
    if(ret!="") return ret;

    for(var i in this.tbars) { ret += this.tbars[i].render(); }
    if(ret!="") return ret;
    return "";
  }
}

/*------------------*/
/* Toolbar */
/*------------------*/
var isTbars=new Object();

function ISToolbar(tId) {
  this.id=tId;
  this.mgr=null;
  this.btns=new Object();
  
  this.btnHeight=25;
  this.btnWidth=23;
  
  this.iconPath="icons/";
  
  this.floating=false;
  
  this.rt=new Object();
  this.rt.sepCnt=0;
  this.rt.brkCnt=0;
  
  this.style={toolbar:"istoolbar"};
  
  isTbars[tId]=this;
  
  return this;
};

var ISTbar=ISToolbar.prototype;

ISTbar.add=function(btn) {
  btn.container=this;
  if(!btn.width)btn.width=this.btnWidth;
  if(!btn.height)btn.height=this.btnHeight;
  this.btns[btn.id]=btn;
  if(this.mgr)this.mgr.btns[btn.id]=btn;
};

ISTbar.addButton=function(id, icon, text, width, height) {
  this.add(new ISButton(id, icon, text, width, height));
};

ISTbar.addToggleButton=function(id, group, checked, icon, text, width, height) {
  this.add(new ISToggleButton(id, group, checked, icon, text, width, height));
}

ISTbar.addDropdownButton=function(id, ddId, icon, text, width, height){
  this.add(new ISDropdownButton(id, ddId, icon, text, width, height));
};

ISTbar.addSeparator=function(icon) {
  var s=new ISSeparator((!icon || icon==""?"brkspace.gif":icon));
  s.id="sep"+ ++this.rt.sepCnt;
  s.container=this;
  s.height=this.btnHeight;
  this.btns[s.id]=s;
  if(this.mgr)this.mgr.btns[s.id]=s;
};

ISTbar.addBreak=function() {
  var s=new ISBreak();
  s.id="brt"+ ++this.rt.brkCnt;
  s.container=this;
  s.height=this.btnHeight;
  this.btns[s.id]=s;
  if(this.mgr)this.mgr.btns[s.id]=s;
};

ISTbar.show=function(x, y) {
  var tb=document.getElementById(this.id);
  tb.style.left=x+"px";
  tb.style.top=y+"px";
  tb.style.display="";
  tb.style.zIndex=100;
  this.rt.active=true;
};

ISTbar.hide=function() {
  var tb=document.getElementById(this.id);
  tb.style.display="none";
  this.rt.active=false;
}

ISTbar.changeState=function() {};
ISTbar.onClick=function(e, s) {
};

ISTbar.render=function() {
  var s=[], j=0;
  s[j++]="<div id='"+this.id+"' style='"+(this.floating?"position:absolute;top:0px;left:0px;display:none":"")+"'><table class='"+this.style.toolbar+"'  cellpadding=0 cellspacing=0 style=\"margin:0px;padding:0px;"+(this.floating?"":"width:100%;")+"\"><tr>";
  if(this.floating) {
    s[j++]="<td unselectable=\"on\" onmousedown=\"$mvmsDown(event, this, '"+this.id+"')\" onmouseover=\"this.style.cursor='move';\" onmouseout=\"this.style.cursor='default'\" style='background-image:url("+this.iconPath+"btndrag.gif)'>";
    s[j++]="&nbsp;</td>";
  }
  s[j++]="<td unselectable='on'>";
  s[j++]="<table cellpadding=0 cellspacing=0 width='100%'><tr><td style='background-image:url("+this.iconPath+"bg.gif);' unselectable='on'>";
  for(var it in this.btns) {
    s[j++]=this.btns[it].toHTML();
  }
  s[j++]="</td></tr></table>";  
  s[j++]="</td></tr></table></div>";
  
  return s.join("");
};

/**/
function ISButton(id, icon, text, width, height) {
  this.id=id;
  this.container=null;
  this.state=1;
  this.text=text;
  this.icon=icon;
  this.height=height;
  this.width=width;
  this.type="STD";
  this.domObj=null;
  
  /*set button state, 1=normal, 2=over 3=down 4=active 5=disable*/
  this.setState=function(s) {
    this.state=s;
    var btn=this.domObj;
    if(!btn) { btn=document.getElementById(this.id).childNodes[0];this.domObj=btn;}
    btn.style.top=-this.height*(s-1)+"px";
  };
  
  this.toHTML=function() {
    var s=[], j=0, tbId=this.container.id;
    s[j++]="<table cellpadding=0 cellspacing=0 align='left'><tr><td style='text-align:left;padding:0px;padding-right:0px;VERTICAL-ALIGN: top;margin-left:0;margin-right:1px;margin-bottom:1px;width:"+this.width+"px;height:"+this.height+"px;'>";
    s[j++]="<div id=\""+id+"\" style=\"position:absolute;clip:rect(0px "+this.width+"px "+this.height+"px 0px);\" onmouseover=\"$msOver(event, '"+tbId+"', '"+this.id+"')\" onmouseout=\"$msOut(event, '"+tbId+"', '"+this.id+"')\" onmousedown=\"$msDown(event, '"+tbId+"', '"+this.id+"')\" onmouseup=\"$msUp(event, '"+tbId+"', '"+this.id+"')\" >";
    s[j++]="<img src=\""+this.container.iconPath+this.icon+"\" style=\"position:absolute;top:0px;left:0px\" alt='"+this.text+"'/>"
    s[j++]="</div>";
    s[j++]="</td></tr></table>";
    
    if (this.type=="DD") s[j++]=isDDs[this.ddId].toHTML();
    return s.join("");
  };
};

function ISToggleButton(id, group, checked, icon, text, width, height) {
  this.constr=ISButton;
  this.constr(id, icon, text, width, height);
  delete this.constr;
  
  this.type="TGL";
  this.checked=checked;
  this.group=group;
};

function ISSeparator(icon) {
  this.icon=icon;
  this.height=25;
  
  this.toHTML=function() {
    var s=[], j=0;
    s[j++]="<table align=left cellpadding=0 cellspacing=0 style='table-layout:fixed;'><tr>";
    s[j++]="<td unselectable='on' style='padding:0px;padding-left:0px;padding-right:0px;VERTICAL-ALIGN:top;margin-bottom:1px;width:5px;height:"+this.height+"px;'><img unselectable='on' src='"+this.container.iconPath+this.icon+"' width='5px'></td>";
    s[j++]="</tr></table>";
    return s.join("");
  }
};

function ISBreak() {  
  this.toHTML=function() {
    var s=[], j=0;
    s[j++]="</td></tr><tr><td style='height:2px'></td></tr><tr><td style='background-image:url("+this.container.iconPath+"bg.gif);height:"+this.height+"px'>";
    return s.join("");
  };
};

function ISDropdownButton(id, ddId, icon, text, width, height) {
  this.constr=ISButton;
  this.constr(id, icon, text, width, height);
  delete this.constr;
  
  this.type="DD";

  this.ddId=ddId;
};

var isDDs=new Object();
function ISDropdown(id) {
  this.id=id;
  this.items=new Object();
  this.maxRowItems=15;
  
  this.add=function(it) { this.items[it.id]=it; it.container=this;};
  this.addItem=function(id, text) {
    this.add(new ISDropdownItem(id, text));
  };
  
  this.enableItem=function(id, f){
    this.items[id].enable=f;
    document.getElementById(id).className=(f?"isdd_norm":"isdd_disb");
  };
  
  this.selectItem=function(id, f) {
    this.clearSelection();
    this.items[id].selected=f;
    document.getElementById(id).className=(f?"isdd_sel":"isdd_norm");
  };
  
  this.clearSelection=function() {
    for(var it in this.items) {
      if(this.items[it].selected) {
        document.getElementById(it).className="isdd_norm";
        this.items[it].selected=false;
      }
    }
  }
  
  this.toHTML=function() {
    var s=[], j=0; it=null; 
    s[j++]="<table id='"+this.id+"' cellpadding=0 cellspacing=0 style='line-height:normal;z-index:1;display:none;position:absolute;border:#80788D 1px solid; cursor:default;background-color:#fbfbfd;' unselectable=on><tr><td>";
    s[j++]="<table cellpadding=0 cellspacing=0>";
    var r=1;
    for (var i in this.items) {
      it=this.items[i];
      s[j++]=it.toHTML();
      if (r%this.maxRowItems==0) {
        s[j++]="</table>";
        s[j++]="</td><td valign=top style='padding:0px;border-left:#80788D 1px solid'>";
        s[j++]="<table cellpadding=0 cellspacing=0>";
      }
      r++;
    }
    s[j++]="</table></td></tr></table>";
    return s.join("");
  };
  
  this.onClick=function(itId) {}
  
  isDDs[id]=this;
};

function ISDropdownItem(id, text) {
  this.id=id;
  this.text=text;
  this.enable=true;
  this.selected=false;
  this.container=null;
  this.toHTML=function() {
    return "<tr><td id='"+this.id+"' onclick=\"$ddmsClick('"+this.container.id+"', '"+this.id+"', this)\" class='"+(this.enable?"isdd_norm":"isdd_disb")+"' onmouseover=\"$ddmsOver('"+this.container.id+"', '"+this.id+"', this)\" onmouseout=\"$ddmsOut('"+this.container.id+"', '"+this.id+"', this)\" unselectable=on align=center nowrap>"+ this.text +"</td></tr>";
  }
};

function ISCustomDDItem(id, s) { 
  this.id=id;
  this.html=s;
  this.toHTML=function() {return ("<tr><td>"+this.html+"</td></tr>"); } 
};

/*------------------*/

/*floating functions*/
function $mvmsDown(e, el, tbId) {
  var tb=isTbars[tbId];
  tb.rt.clOff=(isIE?[e.offsetX, e.offsetY]:[e.layerX, e.layerY]);
  
  var d=document, de=d.documentElement;
  tb.rt.scrl1=(isIE?(de?[de.scrollLeft, de.scrollTop]:[d.body.scrollLeft, d.body.scrollTop]):[window.scrollX, window.scrollY]);

  d.onmousemove=function(e) {$tbStartDrag_1((isIE?event:e), tb, document.getElementById(tbId));}
  d.onmouseup=function(e) {$tbEndDrag((isIE?event:e), tb)}
  d.onselectstart=function() { return false;}
  d.onmousedown=function() { return false;}
  d.ondragstart=function() { return false;}  
  
  d=tb.rt.document;
  //tb.rt.scrl2=(isIE?[d.body.scrollLeft, d.body.scrollTop]:[d.body.scrollX, d.body.scrollY]);
  if(d) {
    d.onmousemove=function(e) {$tbStartDrag_2((isIE?d.parentWindow.event:e), tb, document.getElementById(tbId));}
    d.onmouseup=function(e) {$tbEndDrag((isIE?d.parentWindow.event:e), tb)}
    d.onselectstart=function() { return false;}
    d.onmousedown=function() { return false;}
    d.ondragstart=function() { return false;}   
  }
};

function $tbStartDrag_1(e, tb, oTb) {
  //window.status="x:"+(e.clientX)+"-y:"+(e.clientY);
  oTb.style.left=e.clientX-tb.rt.clOff[0]+tb.rt.scrl1[0] + "px";
  oTb.style.top=e.clientY-tb.rt.clOff[1]+tb.rt.scrl1[1] + "px";
};

function $tbStartDrag_2(e, tb, oTb) {
  //window.status="x:"+(e.clientX)  + "-y:"+(e.clientY);
  oTb.style.left=e.clientX-tb.rt.clOff[0]+tb.rt.docOff[0]+ "px";
  oTb.style.top=e.clientY-tb.rt.clOff[1]+tb.rt.docOff[1]+ "px";
};


function $tbEndDrag(e, tb) {
  //var d=tb.rt.document;
  var d=[document, tb.rt.document];
  for (var i=0;i<d.length;i++) {
    if (!d[i]) continue;
    d[i].onmousemove=null;
    d[i].onmouseup=null;
    d[i].onmousedown=function() { return true;}
    d[i].onselectstart=function() { return true;}
    d[i].onselectstart=function() { return true;}      
  }
};

function $ddmsOver(ddId, id, t) {
  var it=isDDs[ddId].items[id];
  if(!it.enable || it.selected)return;
  t.className="isdd_over";
};

function $ddmsOut(ddId, id, t) {
  var it=isDDs[ddId].items[id];
  if(!it.enable || it.selected)return;
  t.className="isdd_norm";
};

function $ddmsClick(ddId, id, t) {
  if(!isDDs[ddId].items[id].enable)return;
  isDDs[ddId].selectItem(id, true);
  hideDD(ddId);
  isDDs[ddId].onClick(id);
};

/*end of floating functions*/

var $bCancel=false;
function $msOver(e, tbId, btnId) {
  var btn=isTbars[tbId].btns[btnId];
  if(btn.state==1) btn.setState(2);
};

function $msOut(e, tbId, btnId) {
  var btn=isTbars[tbId].btns[btnId];
  if(btn.state==3) {$bCancel=true;}
  if(btn.state==2) btn.setState(1);
};

function $msDown(e, tbId, btnId) {
  var btn=isTbars[tbId].btns[btnId];
  if(btn.state!=5) btn.setState(3);
};

function $msUp(e, tbId, btnId) {
  var tbar=isTbars[tbId];
  var btn=tbar.btns[btnId];
  if($bCancel) {$bCancel=false; btn.setState(1); return false;}
  if(btn.state==5) return false;
  if (btn.type=="STD") {
    btn.setState(2);
    tbar.onClick(btnId);
  } else if(btn.type=="TGL") { 
    if (btn.group!=null && btn.group!="") {
      //find all other button with the same group and set 
      var tBtn=null;
      for (var it in tbar.btns) {
        tBtn=tbar.btns[it];
        if (tBtn.group==btn.group && tBtn.id!=btn.id) {tBtn.setState(1); tBtn.checked=false;}
      }
    }
    //toggle button      
    btn.setState(btn.checked?2:4);
    btn.checked=!btn.checked;
    tbar.onClick(btnId);
  } else if(btn.type=="DD") {
    tbar.onClick(btnId);
    showDD(tbId, btnId, btn.ddId);
    btn.setState(2);
  }
  return true;
};

function showDD(tbId, btnId, ddId) {
  hideAllDD();
  
  var btn=document.getElementById(btnId);
  var dd=document.getElementById(ddId);
  var tmp=btn; var x=0, y=0;
  x=btn.offsetLeft; y=btn.offsetTop;
  dd.style.left=x+"px";
  dd.style.top=y+25+"px";
  dd.style.display="block";

  if (!isDDs[ddId].container) isDDs[ddId].container=isTbars[tbId].btns[btnId];
};

function hideDD(ddId) {
  document.getElementById(ddId).style.display="none";
};

function hideAllDD() {
  for (var tId in isDDs) { hideDD(tId); }
};
/*--------------------*/

var isTGroups=new Object();

function ISToolbarGroup(id) {
  this.id=id;
  this.mgr=null;
  this.grps=new Object();
  this.visible=true;
  
  isTGroups[id]=this;
}
var ISTbarGrp=ISToolbarGroup.prototype;

ISTbarGrp.addGroup=function(id, name, tbId) {
  var g=new ISGroup(id, name, tbId);
  this.grps[id]=g;
};

ISTbarGrp.render=function() {
  var s=[], j=0;
  s[j++]="<table id='"+this.id+"' cellpadding=0 cellspacing=0 border=0 style='"+(this.visible?"":"display:none;")+"'><tr>";
  for (var it in this.grps) {
    s[j++]="<td unselectable='on'>"+this.grps[it].render()+"</td>";
  }  
  s[j++]="</tr></table>";
  return s.join("");
};

ISTbarGrp.setVisibility=function(b) {
  this.visible=b;
  var e=document.getElementById(this.id);
  if(e) e.style.display=(b?"":"none");
};

function ISGroup(id, name, tbId) {
  this.id=id;
  this.name=name;
  this.tbId=tbId;
  return this;
};

ISGroup.prototype.render=function() {
  var s=[], j=0;
  s[j++]="<table cellpadding=0 cellspacing=0 style='margin-right:3px;font-size:8px;' unselectable='on'>";
  s[j++]="<tr><td class='bdrgrptopleft'>&nbsp;</td><td class='bdrgrptop'></td><td class='bdrgrptopright'>&nbsp;</td></tr>";
  s[j++]="<tr><td colspan='3' width='100%'>";
  
  s[j++]="<table cellpadding=0 cellspacing=0 class='isgroup' width='100%' style='font-size:8px;'><tr><td class='bdrgrpleft'></td><td class='isgroupcontent' unselectable='on'>";
  s[j++]=isTbars[this.tbId].render();
  s[j++]="</td><td class='bdrgrpright'></td></tr>";  
  //s[j++]="<tr><td class='bdrgrpleft'></td><td class='isgrouptitle' align='center'>";
  //s[j++]=this.name;
  //s[j++]="</td><td class='bdrgrpright'></td></tr>";
  s[j++]="</table>";
  
  s[j++]="</td></tr>";
  s[j++]="<tr><td class='bdrgrpbottomleft'>&nbsp;</td><td class='bdrgrpbottom'></td><td class='bdrgrpbottomright'>&nbsp;</td></tr>";
  s[j++]="</table>";
  

  return s.join("");  
};

/*--------------------*/
var isTabs=new Object();

function ISTabCtl(id) {
  this.id=id;
  this.mgr=null;
  this.tabs=new Object();
  this.tabIdx=[];
  this.selTab=null;
  isTabs[id]=this;
  return this;
};

function ISTab(id, capt, obj) {
  this.id=id;
  this.capt=capt;
  this.obj=obj;
  this.selected=false;
  return this;
};

ISTab.prototype.render=function() {
  var s=[], j=0, sf=(this.selected?"sel":"")
  s[j++]="<table id='"+this.id+"' cellpadding=0 cellspacing=0 class='istab' align='left' onclick=\"isTabs."+this.tab.id+".setTab('"+this.id+"')\" style='cursor:default;' unselectable='on'><tr>";
  s[j++]="<td class='tableft"+sf+"' width='5px'></td>";
  s[j++]="<td class='tabtitle"+sf+"' unselectable='on'>"+this.capt+"</td>";
  s[j++]="<td class='tabright"+sf+"' width='5px'></td>";
  s[j++]="</tr></table>";
  return s.join("");    
};

ISTabCtl.prototype.addTab=function(id, capt, obj) {
  var t=new ISTab(id, capt, obj);
  t.tab=this;
  this.tabs[id]=t;
  if(this.tabIdx.length==0) this.selTab=id;
  this.tabIdx[this.tabIdx.length]=id;
};

ISTabCtl.prototype.render=function() {
  var s=[], j=0, o=null;
  s[j++]="<table cellpadding=0 cellspacing=0 class='istabctl'><tr><td class='bdrtabtopleft' unselectable='on'></td><td class='bdrtabtop' unselectable=\"on\">";
  for (var it in this.tabs) {
    o=this.tabs[it];
    o.selected=(this.selTab==o.id);
    s[j++]=o.render(); 
  }
  s[j++]="</td><td class='bdrtabtopright'></td></tr>";
  s[j++]="<tr><td class='bdrtableft' style='font-size:7pt'>&nbsp;</td><td class='tabcontent' unselectable='on'>";
  for (var it in this.tabs) { 
    o=this.tabs[it].obj;
    o.visible=(this.selTab==this.tabs[it].id);
    s[j++]=this.tabs[it].obj.render(); 
  }
  s[j++]="</td><td class='bdrtabright' style='font-size:7pt'>&nbsp;</td></tr>";
  s[j++]="<tr><td class='bdrtabbottomleft'></td><td class='bdrtabbottom'></td><td class='bdrtabbottomright'></td></tr>";
  s[j++]="</table>";
  return s.join("");
};

ISTabCtl.prototype.setTab=function(id) {
  //current selected
  var t=document.getElementById(this.selTab);
  if (t) {
    t.rows[0].cells[0].className="tableft";
    t.rows[0].cells[1].className="tabtitle";
    t.rows[0].cells[2].className="tabright";
  }
  this.tabs[this.selTab].selected=false;
  this.tabs[this.selTab].obj.setVisibility(false);
  
  t=document.getElementById(id);
  t.rows[0].cells[0].className="tableftsel";
  t.rows[0].cells[1].className="tabtitlesel";
  t.rows[0].cells[2].className="tabrightsel";
  this.tabs[id].selected=true; 
  this.tabs[id].obj.setVisibility(true);
  this.selTab=id;
};