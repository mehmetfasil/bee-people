function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Fonte";
	txtLang[1].innerHTML = "Segnalibro";
	txtLang[2].innerHTML = "Obiettivo";
	txtLang[3].innerHTML = "Titolo";

    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Finestra Corrente (Self)"
	optLang[1].text = "Nuova Finestra (Blank)"
	optLang[2].text = "Finestra Madre (Parent)"
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Hyperlink</title>")
    }