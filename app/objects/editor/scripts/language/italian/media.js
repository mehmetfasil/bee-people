function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Fonte";
	txtLang[1].innerHTML = "Larghezza";
	txtLang[2].innerHTML = "Altezza";	
	txtLang[3].innerHTML = "Auto inizio";	
	txtLang[4].innerHTML = "Mostra Controls";
	txtLang[5].innerHTML = "Mostra Barra di stato";	
	txtLang[6].innerHTML = "Mostra Display";
	txtLang[7].innerHTML = "Auto Rewind";	

    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Media</title>")
    }
