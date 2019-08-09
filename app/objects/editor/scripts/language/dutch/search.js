function loadTxt()
	{
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Zoeken";
    txtLang[1].innerHTML = "Vervangen";
    txtLang[2].innerHTML = "Ident. hoofd/kleine lett.";
    txtLang[3].innerHTML = "Heel woord";
    
    document.getElementById("btnSearch").value = "zoek volgende";;
    document.getElementById("btnReplace").value = "vervangen";
    document.getElementById("btnReplaceAll").value = "alles vervangen";  
    document.getElementById("btnClose").value = "sluiten";
	}
function getTxt(s)
    {
    switch(s)
        {
        case "Finished searching": return "Het doorzoeken van het document is voltooid.\nOpnieuw zoeken vanaf het begin?";
        default: return "";
        }
    }
function writeTitle()
	{
	document.write("<title>Zoeken & Vervangen</title>")
	}