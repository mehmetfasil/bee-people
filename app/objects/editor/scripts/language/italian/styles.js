function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Stile";
	txtLang[1].innerHTML = "Anteprima";
	txtLang[2].innerHTML = "Applica";
    
    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Seleziona Testo";
	optLang[1].text = "Tag Corrente";
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
    
function getTxt(s)
    {
    switch(s)
        {
		case "You're selecting BODY element.":
			return "Stai selezionano elementi del corpo del testo.";
		case "Please select a text.":
			return "Prego selezionare un testo.";
		default:return "";
        }
    }
    
function writeTitle()
    {
    document.write("<title>Stile</title>")
    }
