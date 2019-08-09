function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Tipo";
	txtLang[1].innerHTML = "Nome";
	txtLang[2].innerHTML = "Valore";

    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Pulsante"
	optLang[1].text = "Approva"
	optLang[2].text = "Cambia"
        
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Pulsante</title>")
    }