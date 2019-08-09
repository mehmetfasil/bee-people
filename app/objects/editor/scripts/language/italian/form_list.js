function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Nome";
	txtLang[1].innerHTML = "Misura";
	txtLang[2].innerHTML = "Selezione Multipla";
	txtLang[3].innerHTML = "n. opzioni mostrate";
    
    document.getElementById("btnAdd").value = "  aggiungi  ";
    document.getElementById("btnUp").value = "  sopra  ";
    document.getElementById("btnDown").value = "  sotto  ";
    document.getElementById("btnDel").value = "  rimuovi  ";
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Lista di riepilogo a discesa</title>")
    }