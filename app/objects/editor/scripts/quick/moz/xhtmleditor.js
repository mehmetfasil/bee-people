var oUtil = new EditorUtil();

var onloadOverrided = false;

function onload_new()
  { 
  onload_original();  
  setMozEdit();
  }
  
function onload_original()
  {
  }  

function setMozEdit(oName) 
  { 
    if ((oName != null) && (oName!="")) {
        try {document.getElementById("idContent" + oName).contentDocument.designMode="on";} catch(e) {}
    } else {
        for (var i=0; i<oUtil.arrEditor.length; i++)
        {
        try {document.getElementById("idContent" + oUtil.arrEditor[i]).contentDocument.designMode="on";} catch(e) {alert(e)}
        }
    }
  } 

function EditorUtil() {
    this.obj = null;
    this.oEditor = null;
    this.arrEditor = [];
}

function InnovaEditor(oName) {
    this.oName = oName;
    this.height = "400px";
    this.width = "100%";
    
    this.RENDER = RENDER;
    this.doCmd = edt_doCmd;
    this.getHTMLBody = edt_getHTMLBody;
    this.getXHTMLBody = edt_getXHTMLBody;
    this.insertHTML = edt_insertHTML;
    this.cleanDeprecated = edt_cleanDeprecated;
    this.cleanEmptySpan =  edt_cleanEmptySpan;
    this.cleanFonts = edt_cleanFonts;
    this.cleanTags = edt_cleanTags; 
    this.replaceTags = edt_replaceTags;
    this.toggleViewSource = edt_toggleViewSource;
    this.viewSource = edt_viewSource;
    this.applySource = edt_applySource;
}

function RENDER() {
}

function edt_doCmd(sCmd,sOption)
	{
    var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
    oEditor.document.execCommand(sCmd,false,sOption);
    }

function edt_getHTMLBody()
	{
    var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
    sHTML=oEditor.document.body.innerHTML;
    sHTML=String(sHTML).replace(/ contentEditable=true/g,"");
    sHTML = String(sHTML).replace(/\<PARAM NAME=\"Play\" VALUE=\"0\">/ig,"<PARAM NAME=\"Play\" VALUE=\"-1\">");
    return sHTML;
	}

function edt_getXHTMLBody()
  {
	if (document.getElementById("chkViewSource"+this.oName).checked) 
	    {
            var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
	        return oEditor.document.body.textContent;
        } else {
            var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
            this.cleanDeprecated();

            return recur(oEditor.document.body,"");
        }    
  }

/*Insert custon HTML function*/
function edt_insertHTML(sHTML)
  {
  var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
  var oSel=oEditor.getSelection(); 
  var range = oSel.getRangeAt(0);
    
  var docFrag = range.createContextualFragment(sHTML);
  range.collapse(true);
  var lastNode = docFrag.childNodes[docFrag.childNodes.length-1];
  range.insertNode(docFrag);
  try { oEditor.document.designMode="on"; } catch (e) {}
  if (lastNode.nodeType==Node.TEXT_NODE) 
    {
    range = oEditor.document.createRange();
    range.setStart(lastNode, lastNode.nodeValue.length);
    range.setEnd(lastNode, lastNode.nodeValue.length);
    oSel = oEditor.getSelection();
    oSel.removeAllRanges();
    oSel.addRange(range);
    }
  }

/************************************
	CLEAN DEPRECATED TAGS; Used in loadHTML, getHTMLBody, getXHTMLBody 
*************************************/
function edt_cleanDeprecated()
  {
    var oEditor=document.getElementById("idContent"+this.oName).contentWindow;

  var elements;

  elements=oEditor.document.body.getElementsByTagName("STRIKE");
  this.cleanTags(elements,"line-through");
  elements=oEditor.document.body.getElementsByTagName("S");
  this.cleanTags(elements,"line-through");
  
  elements=oEditor.document.body.getElementsByTagName("U");
  this.cleanTags(elements,"underline");

  this.replaceTags("DIR","DIV");
  this.replaceTags("MENU","DIV"); 
  this.replaceTags("CENTER","DIV");
  this.replaceTags("XMP","PRE");
  this.replaceTags("BASEFONT","SPAN");//will be removed by cleanEmptySpan()
  
  elements=oEditor.document.body.getElementsByTagName("APPLET");
  while(elements.length>0) 
    {
    var f = elements[0];
    theParent = f.parentNode;
    theParent.removeChild(f);
    }
  
  this.cleanFonts();
  this.cleanEmptySpan();

  return true;
  }

function edt_cleanEmptySpan()
  {
  var bReturn=false;
  var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
  var reg = /<\s*SPAN\s*>/gi;

  while (true) 
    {
    var allSpans = oEditor.document.getElementsByTagName("SPAN");
    if(allSpans.length==0) break;

    var emptySpans = []; 
    for (var i=0; i<allSpans.length; i++) 
      {
      if (getOuterHTML(allSpans[i]).search(reg) == 0)
        emptySpans[emptySpans.length]=allSpans[i];
      }
    if (emptySpans.length == 0) break;
    var theSpan, theParent;
    for (var i=0; i<emptySpans.length; i++) 
      {
      theSpan = emptySpans[i];
      theParent = theSpan.parentNode;
      if (!theParent) continue;
      if (theSpan.hasChildNodes()) 
        {
        var range = oEditor.document.createRange();
        range.selectNodeContents(theSpan);
        var docFrag = range.extractContents();
        theParent.replaceChild(docFrag, theSpan);
        } 
      else 
        {
        theParent.removeChild(theSpan);
        }
      bReturn=true;
      }
    }
  return bReturn;
  }
	
function edt_cleanFonts() 
  {
  var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
  var allFonts = oEditor.document.body.getElementsByTagName("FONT");
  if(allFonts.length==0)return false;
  
  var f; var range;
  while (allFonts.length>0) 
    {
    f = allFonts[0];
    if (f.hasChildNodes && f.childNodes.length==1 && f.childNodes[0].nodeType==1 && f.childNodes[0].nodeName=="SPAN") 
      {
      //if font containts only span child node
      
      var theSpan = f.childNodes[0];
      copyAttribute(theSpan, f);
      
      range = oEditor.document.createRange();
      range.selectNode(f);
      range.insertNode(theSpan);
      range.selectNode(f);
      range.deleteContents();
      } 
    else 
      if (f.parentNode.nodeName=="SPAN" && f.parentNode.childNodes.length==1) 
        {
        //font is the only child node of span.
        var theSpan = f.parentNode;
        copyAttribute(theSpan, f);
        theSpan.innerHTML = f.innerHTML;
        } 
      else 
        {
        var newSpan = oEditor.document.createElement("SPAN");
        copyAttribute(newSpan, f);
        newSpan.innerHTML = f.innerHTML;
        f.parentNode.replaceChild(newSpan, f);
        }
    }
  return true;
  }
function edt_cleanTags(elements,sVal)
  {
  var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
  if(elements.length==0)return false;
  
  var f;var range;
  while(elements.length>0) 
    {
    f = elements[0];
    if(f.hasChildNodes && f.childNodes.length==1 && f.childNodes[0].nodeType==1 && f.childNodes[0].nodeName=="SPAN") 
      {//if font containts only span child node      
      var theSpan=f.childNodes[0];
      if(sVal=="bold")theSpan.style.fontWeight="bold";
      if(sVal=="italic")theSpan.style.fontStyle="italic";
      if(sVal=="line-through")theSpan.style.textDecoration="line-through";
      if(sVal=="underline")theSpan.style.textDecoration="underline";

      range=oEditor.document.createRange();
      range.selectNode(f);
      range.insertNode(theSpan);
      range.selectNode(f);
      range.deleteContents();
      } 
    else 
      if (f.parentNode.nodeName=="SPAN" && f.parentNode.childNodes.length==1) 
        {
        //font is the only child node of span.
        var theSpan=f.parentNode;
        if(sVal=="bold")theSpan.style.fontWeight="bold";
        if(sVal=="italic")theSpan.style.fontStyle="italic";
        if(sVal=="line-through")theSpan.style.textDecoration="line-through";
        if(sVal=="underline")theSpan.style.textDecoration="underline";
        
        theSpan.innerHTML=f.innerHTML;
        } 
      else 
        {
        var newSpan = oEditor.document.createElement("SPAN");
        if(sVal=="bold")newSpan.style.fontWeight="bold";
        if(sVal=="italic")newSpan.style.fontStyle="italic";
        if(sVal=="line-through")newSpan.style.textDecoration="line-through";
        if(sVal=="underline")newSpan.style.textDecoration="underline";

        newSpan.innerHTML=f.innerHTML;
        f.parentNode.replaceChild(newSpan,f);
        }
    }
  return true;
  }

function edt_replaceTags(sFrom,sTo)
  {
  var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
  
  var elements=oEditor.document.body.getElementsByTagName(sFrom);
  
  while(elements.length>0) 
    {
    f = elements[0];
    
    var newSpan = oEditor.document.createElement(sTo);
    newSpan.innerHTML=f.innerHTML;
    f.parentNode.replaceChild(newSpan,f);
    }
  }
function copyAttribute(newSpan,f) 
    {
    if ((f.face != null) && (f.face != ""))newSpan.style.fontFamily=f.face;
    if ((f.size != null) && (f.size != ""))
        {
        var nSize="";
        if(f.size==1)nSize="8pt";
        else if(f.size==2)nSize="10pt";
        else if(f.size==3)nSize="12pt";
        else if(f.size==4)nSize="14pt";
        else if(f.size==5)nSize="18pt";
        else if(f.size==6)nSize="24pt";
        else if(f.size>=7)nSize="36pt";
        else if(f.size<=-2||f.size=="0")nSize="8pt";
        else if(f.size=="-1")nSize="10pt";
        else if(f.size==0)nSize="12pt";
        else if(f.size=="+1")nSize="14pt";
        else if(f.size=="+2")nSize="18pt";
        else if(f.size=="+3")nSize="24pt";
        else if(f.size=="+4"||f.size=="+5"||f.size=="+6")nSize="36pt";
        else nSize="";
        if(nSize!="")newSpan.style.fontSize=nSize;
        }
    if ((f.style.backgroundColor != null)&&(f.style.backgroundColor != ""))newSpan.style.backgroundColor=f.style.backgroundColor;
    if ((f.color != null)&&(f.color != ""))newSpan.style.color=f.color;
    }
function GetElement(oElement,sMatchTag)//Used in realTime() only.
    {
    while (oElement!=null&&oElement.tagName!=sMatchTag)
        {
        if(oElement.tagName=="BODY")return null;
        oElement=oElement.parentNode;
        }
    return oElement;
    }

/************************************
	HTML to XHTML
*************************************/
function lineBreak1(tag) //[0]<TAG>[1]text[2]</TAG>
  {
  arrReturn = ["\n","",""];
  if( tag=="A"||tag=="B"||tag=="CITE"||tag=="CODE"||tag=="EM"|| 
    tag=="FONT"||tag=="I"||tag=="SMALL"||tag=="STRIKE"||tag=="BIG"||
    tag=="STRONG"||tag=="SUB"||tag=="SUP"||tag=="U"||tag=="SAMP"||
    tag=="S"||tag=="VAR"||tag=="BASEFONT"||tag=="KBD"||tag=="TT") 
    arrReturn=["","",""];

  if( tag=="TEXTAREA"||tag=="TABLE"||tag=="THEAD"||tag=="TBODY"|| 
    tag=="TR"||tag=="OL"||tag=="UL"||tag=="DIR"||tag=="MENU"|| 
    tag=="FORM"||tag=="SELECT"||tag=="MAP"||tag=="DL"||tag=="HEAD"|| 
    tag=="BODY"||tag=="HTML") 
    arrReturn=["\n","","\n"];

  if( tag=="STYLE"||tag=="SCRIPT")
    arrReturn=["\n","",""];

  if(tag=="BR"||tag=="HR") 
    arrReturn=["","\n",""];

  return arrReturn;
  }  
function fixAttr(s)
  {
  s = String(s).replace(/&/g, "&amp;");
  s = String(s).replace(/</g, "&lt;");
  s = String(s).replace(/"/g, "&quot;");
  return s;
  }  
function fixVal(s)
  {
  s = String(s).replace(/&/g, "&amp;");
  s = String(s).replace(/</g, "&lt;");
  var x = escape(s);
  x = unescape(x.replace(/\%A0/gi, "-*REPL*-"));
  s = x.replace(/-\*REPL\*-/gi, "&nbsp;");
  return s;
  }  
function recur(oEl,sTab)
  {
  var sHTML="";
  for(var i=0;i<oEl.childNodes.length;i++)
    {
    var oNode=oEl.childNodes[i];
    if(oNode.nodeType==1)//tag
      {
      var sTagName = oNode.nodeName;

      var bDoNotProcess=false;
      if(sTagName.substring(0,1)=="/")
        {
        bDoNotProcess=true;//do not process
        }
      else
        {
        /*** tabs ***/
        var sT= sTab;
        sHTML+= lineBreak1(sTagName)[0];  
        if(lineBreak1(sTagName)[0] !="") sHTML+= sT;//If new line, use base Tabs
        /************/
        }

      if(bDoNotProcess)
        {
        ;//do not process
        }
      else if(sTagName=="OBJECT" || sTagName=="EMBED")
        {   
        s=getOuterHTML(oNode);

        s=s.replace(/\"[^\"]*\"/ig,function(x){           
            x=x.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/'/g, "&apos;").replace(/\s+/ig,"#_#");
            return x});
        s=s.replace(/<([^ >]*)/ig,function(x){return x.toLowerCase()})            
        s=s.replace(/ ([^=]+)=([^"' >]+)/ig," $1=\"$2\"");//new
        
        s=s.replace(/ ([^=]+)=/ig,function(x){return x.toLowerCase()});
        s=s.replace(/#_#/ig," ");
        
        s=s.replace(/<param([^>]*)>/ig,"\n<param$1 />").replace(/\/ \/>$/ig," \/>");//no closing tag

        if(sTagName=="EMBED")
          if(oNode.innerHTML=="")
            s=s.replace(/>$/ig," \/>").replace(/\/ \/>$/ig,"\/>");//no closing tag
        
        s=s.replace(/<param name=\"Play\" value=\"0\" \/>/,"<param name=\"Play\" value=\"-1\" \/>")
        
        sHTML+=s;
        }
      else if(sTagName=="TITLE")
        {
        sHTML+="<title>"+oNode.innerHTML+"</title>";
        }
      else
        {
        if(sTagName=="AREA")
          {
          var sCoords=oNode.coords;
          var sShape=oNode.shape;
          }
          
        var oNode2=oNode.cloneNode(false);       
        s=getOuterHTML(oNode2).replace(/<\/[^>]*>/,"");
        
        if(sTagName=="STYLE")
          {
          var arrTmp=s.match(/<[^>]*>/ig);
          s=arrTmp[0];
          }       

        s=s.replace(/\"[^\"]*\"/ig,function(x){
            //x=x.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/'/g, "&apos;").replace(/\s+/ig,"#_#");
            //x=x.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\s+/ig,"#_#");
            x=x.replace(/&/g, "&amp;").replace(/&amp;amp;/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\s+/ig,"#_#");
            return x});
            //info ttg: .replace(/&amp;amp;/g, "&amp;")
            //ini karena '&' di (hanya) '&amp;' selalu di-replace lagi dgn &amp;.
            //tapi kalau &lt; , &gt; tdk (no problem) => default behaviour
    
        s=s.replace(/<([^ >]*)/ig,function(x){return x.toLowerCase()})            
        s=s.replace(/ ([^=]+)=([^" >]+)/ig," $1=\"$2\"");
        s=s.replace(/ ([^=]+)=/ig,function(x){return x.toLowerCase()});
        s=s.replace(/#_#/ig," ");
        
        //single attribute
        s=s.replace(/[<hr]?(noshade="")/ig,"noshade=\"noshade\"");
        s=s.replace(/[<input]?(checked="")/ig,"checked=\"checked\"");
        s=s.replace(/[<select]?(multiple="")/ig,"multiple=\"multiple\"");
        s=s.replace(/[<option]?(selected="")/ig,"selected=\"true\"");
        s=s.replace(/[<input]?(readonly="")/ig,"readonly=\"readonly\"");
        s=s.replace(/[<input]?(disabled="")/ig,"disabled=\"disabled\"");
        s=s.replace(/[<td]?(nowrap="" )/ig,"nowrap=\"nowrap\" ");
        s=s.replace(/[<td]?(nowrap=""\>)/ig,"nowrap=\"nowrap\"\>");
        
        s=s.replace(/ contenteditable=\"true\"/ig,"");
        
        if(sTagName=="AREA")
          {
          s=s.replace(/ coords=\"0,0,0,0\"/ig," coords=\""+sCoords+"\"");
          s=s.replace(/ shape=\"RECT\"/ig," shape=\""+sShape+"\"");
          }
          
        var bClosingTag=true;
        if(sTagName=="IMG"||sTagName=="BR"||
          sTagName=="AREA"||sTagName=="HR"|| 
          sTagName=="INPUT"||sTagName=="BASE"||
          sTagName=="LINK")//no closing tag
          {
          s=s.replace(/>$/ig," \/>").replace(/\/ \/>$/ig,"\/>");//no closing tag
          bClosingTag=false;      
          }         
        
        sHTML+=s;       
          
        /*** tabs ***/
        if(sTagName!="TEXTAREA")sHTML+= lineBreak1(sTagName)[1];
        if(sTagName!="TEXTAREA")if(lineBreak1(sTagName)[1] !="") sHTML+= sT;//If new line, use base Tabs
        /************/  
        
        if(bClosingTag)
          {
          /*** CONTENT ***/
          s=getOuterHTML(oNode);
          if(sTagName=="SCRIPT")
            {
            s = s.replace(/<script([^>]*)>[\n+\s+\t+]*/ig,"<script$1>");//clean spaces
            s = s.replace(/[\n+\s+\t+]*<\/script>/ig,"<\/script>");//clean spaces
            s = s.replace(/<script([^>]*)>\/\/<!\[CDATA\[/ig,"");
            s = s.replace(/\/\/\]\]><\/script>/ig,"");
            s = s.replace(/<script([^>]*)>/ig,"");
            s = s.replace(/<\/script>/ig,"");         
            s = s.replace(/^\s+/,'').replace(/\s+$/,'');            

            sHTML+="\n"+
              sT + "//<![CDATA[\n"+
              sT + s + "\n" +
              sT + "//]]>\n"+sT;

            }
          if(sTagName=="STYLE")
            {       
            s = s.replace(/<style([^>]*)>[\n+\s+\t+]*/ig,"<style$1>");//clean spaces
            s = s.replace(/[\n+\s+\t+]*<\/style>/ig,"<\/style>");//clean spaces         
            s = s.replace(/<style([^>]*)><!--/ig,"");
            s = s.replace(/--><\/style>/ig,"");
            s = s.replace(/<style([^>]*)>/ig,"");
            s = s.replace(/<\/style>/ig,"");          
            s = s.replace(/^\s+/,"").replace(/\s+$/,"");            
            
            sHTML+="\n"+
              sT + "<!--\n"+
              sT + s + "\n" +
              sT + "-->\n"+sT;
            }
          if(sTagName=="DIV"||sTagName=="P")
            {
            if(oNode.innerHTML==""||oNode.innerHTML=="&nbsp;") 
              {
              sHTML+="&nbsp;";
              }
            else sHTML+=recur(oNode,sT+"\t");
            }
          else
          if (sTagName == "STYLE" || sTagName=="SCRIPT")
            {
            //do nothing
            }
          else
            {
            sHTML+=recur(oNode,sT+"\t");  
            }         
            
          /*** tabs ***/
          if(sTagName!="TEXTAREA")sHTML+=lineBreak1(sTagName)[2];
          if(sTagName!="TEXTAREA")if(lineBreak1(sTagName)[2] !="")sHTML+=sT;//If new line, use base Tabs
          /************/
            
          sHTML+="</" + sTagName.toLowerCase() + ">";
          }     
        }     
      }
    else if(oNode.nodeType==3)//text
      {
      sHTML+= fixVal(oNode.nodeValue);
      }
    else if(oNode.nodeType==8)
      {
      if(getOuterHTML(oNode).substring(0,2)=="<"+"%")
        {//server side script
        sTmp=(getOuterHTML(oNode).substring(2))
        sTmp=sTmp.substring(0,sTmp.length-2)
        sTmp = sTmp.replace(/^\s+/,"").replace(/\s+$/,"");
        
        /*** tabs ***/
        var sT= sTab;
        /************/
        
        sHTML+="\n" +
          sT + "<%\n"+
          sT + sTmp + "\n" +
          sT + "%>\n"+sT;
        }
      else
        {//comments

        /*** tabs ***/
        var sT= sTab;
        /************/
        
        sTmp=oNode.nodeValue;
        sTmp = sTmp.replace(/^\s+/,"").replace(/\s+$/,"");
        
        sHTML+="\n" +
          sT + "<!--\n"+
          sT + sTmp + "\n" +
          sT + "-->\n"+sT;
        }
      }
    else
      {
      ;//Not Processed
      }
    }
  return sHTML;
  }

function getOuterHTML(node) 
  {
    var sHTML = "";
    switch (node.nodeType) 
    {
        case Node.ELEMENT_NODE:
            sHTML = "<" + node.nodeName;
            
            var tagVal ="";
            for (var atr=0; atr < node.attributes.length; atr++) 
        {       
                if (node.attributes[atr].nodeName.substr(0,4) == "_moz" ) continue;
                if (node.attributes[atr].nodeValue.substr(0,4) == "_moz" ) continue;//yus                
                if (node.nodeName=='TEXTAREA' && node.attributes[atr].nodeName.toLowerCase()=='value') 
          {
                    tagVal = node.attributes[atr].nodeValue;
          } 
        else 
          {
                    sHTML += ' ' + node.attributes[atr].nodeName + '="' + node.attributes[atr].nodeValue + '"';
          }
        }
            sHTML += '>'; 
            sHTML += (node.nodeName!='TEXTAREA' ? node.innerHTML : tagVal);
            sHTML += "</"+node.nodeName+">";
            break;
        case Node.COMMENT_NODE:
            sHTML = "<!"+"--"+node.nodeValue+ "--"+">"; break;
        case Node.TEXT_NODE:
            sHTML = node.nodeValue; break;
    }
    return sHTML
  }
  
function edt_toggleViewSource(chk) {
    if (chk.checked) {
        //view souce
        this.viewSource();
    } else {
        //wysiwyg mode
        this.applySource();
    }
}

function edt_viewSource() {
    var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
    
    this.cleanDeprecated();
    var sHTML=recur(oEditor.document.body,"");

    var docBody = oEditor.document.body;
    docBody.innerHTML = "";
    docBody.appendChild(oEditor.document.createTextNode(sHTML));
}

function edt_applySource() {
    var oEditor=document.getElementById("idContent"+this.oName).contentWindow;
    
    var range = oEditor.document.body.ownerDocument.createRange();
    range.selectNodeContents(oEditor.document.body);
    var sHTML = range.toString();
    sHTML = sHTML.replace(/>\s+</gi, "><"); //replace space between tag
    sHTML = sHTML.replace(/\r/gi, ""); //replace space between tag
    sHTML = sHTML.replace(/(<br>)\s+/gi, "$1"); //replace space between BR and text
    sHTML = sHTML.replace(/(<br\s*\/>)\s+/gi, "$1"); //replace space between <BR/> and text. spasi antara <br /> menyebebkan content menggeser kekanan saat di apply
    sHTML = sHTML.replace(/\s+/gi, " "); //replace spaces with space    
    oEditor.document.body.innerHTML = sHTML;
}