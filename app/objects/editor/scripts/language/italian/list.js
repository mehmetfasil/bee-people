function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Elenco Numerato";
	txtLang[1].innerHTML = "Elenco Puntato";
	txtLang[2].innerHTML = "Numero Iniziale";
	txtLang[3].innerHTML = "Margine Sinistro";
	txtLang[4].innerHTML = "Immagine in uso - url"
	txtLang[5].innerHTML = "Margine Sinistro";
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";	
 
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Please select a list.":return "Prego selezionare una lista.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Formatta Lista</title>")
    }
