function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Nome";
	txtLang[1].innerHTML = "Valore";
	txtLang[2].innerHTML = "Predefinita";

    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Selezionata"
	optLang[1].text = "Tralasciata"
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Scelta Opzioni</title>")
    }