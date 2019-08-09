function loadTxt()
    {
    document.getElementById("txtLang").innerHTML = "Avvolgi Testo";
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
		case "Search":return "Trova";
		case "Cut":return "Taglia";
		case "Copy":return "Copia";
		case "Paste":return "Incolla";
		case "Undo":return "Annulla";
		case "Redo":return "Ripristina";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Fonte Editore</title>")
    }
