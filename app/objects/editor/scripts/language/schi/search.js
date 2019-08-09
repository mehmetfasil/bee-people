function loadTxt()
	{
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "\u641c\u7d22 ";
    txtLang[1].innerHTML = "\u66ff\u6362 ";
    txtLang[2].innerHTML = "\u5927\u5c0f\u5199\u987b\u76f8\u7b26 ";
    txtLang[3].innerHTML = "\u5168\u5b57\u62fc\u5199\u987b\u76f8\u7b26 ";
    
    document.getElementById("btnSearch").value = "\u5bfb\u627e\u4e0b\u4e00\u4e2a ";;
    document.getElementById("btnReplace").value = "\u66ff\u6362 ";
    document.getElementById("btnReplaceAll").value = "\u5168\u90e8\u66ff\u6362 ";
    document.getElementById("btnClose").value = "\u5173\u95ed ";
	}
function getTxt(s)
    {
    switch(s)
        {
        case "Finished searching": return "\u6587\u6863\u641c\u7d22\u7ed3\u675f .\n\u662f\u5426\u4ece\u5934\u5f00\u59cb\u641c\u7d22?";
        default: return "";
        }
    }
function writeTitle()
	{
	document.write("<title>\u641c\u7d22\u548c\u66ff\u6362  </title>")
	}
