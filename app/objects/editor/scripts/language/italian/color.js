function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Tavolozza Web";
	txtLang[1].innerHTML = "Colore con Nome";
	txtLang[2].innerHTML = "216 Web Safe";
	txtLang[3].innerHTML = "Nuovo";
	txtLang[4].innerHTML = "Corrente";
	txtLang[5].innerHTML = "Colori scelti";
    
    document.getElementById("btnAddToCustom").value = "Aggiungi Colore";
    document.getElementById("btnCancel").value = " cancella ";
    document.getElementById("btnRemove").value = " rimuovi colori ";
    document.getElementById("btnApply").value = " applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Colori</title>")
    }
