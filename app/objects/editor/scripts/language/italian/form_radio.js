function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Nome";
	txtLang[1].innerHTML = "Valore";
	txtLang[2].innerHTML = "Predefinito";

    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Selezionato"
	optLang[1].text = "Tralasciato"
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Pulsante Radio</title>")
    }