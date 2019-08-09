function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Digita";
	txtLang[1].innerHTML = "Nome";
	txtLang[2].innerHTML = "Misura";
	txtLang[3].innerHTML = "Max Lunghezza";
	txtLang[4].innerHTML = "Num Linea";
	txtLang[5].innerHTML = "Valore";

    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Testo"
	optLang[1].text = "Area Testo"
	optLang[2].text = "Password"
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Campo Testo</title>")
    }