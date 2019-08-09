function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Nome";
	txtLang[1].innerHTML = "Valore";
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Campo nascosto</title>")
    }
