function loadTxt()
    {
    var txtLang =  document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Riga";
	txtLang[1].innerHTML = "Spazio";
	txtLang[2].innerHTML = "Colonna";
	txtLang[3].innerHTML = "Spessore Cornice";
	txtLang[4].innerHTML = "Bordi";
	txtLang[5].innerHTML = "Crolla";
    
	var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Senza Bordi"
	optLang[1].text = "Si"
	optLang[2].text = "No"
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";

    document.getElementById("btnSpan1").value = "Span v";
    document.getElementById("btnSpan2").value = "Span >";
    }
function writeTitle()
    {
    document.write("<title>Inserisci Tabella</title>")
    }