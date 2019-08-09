function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Inserisci Riga";
    txtLang[1].innerHTML = "Inserisci Colonna";
    txtLang[2].innerHTML = "Aumenta/Diminuisci<br>UnioneRiga";
    txtLang[3].innerHTML = "Aumenta/Diminuisci<br>UnioneColonna";
    txtLang[4].innerHTML = "Rimuovi Riga";
    txtLang[5].innerHTML = "Rimuovi Colonna";

	document.getElementById("btnInsRowAbove").title="Inserisci Riga (Sopra)";
	document.getElementById("btnInsRowBelow").title="Inserisci Riga (Sotto)";
	document.getElementById("btnInsColLeft").title="Inserisci Colonna (Sinistra)";
	document.getElementById("btnInsColRight").title="Inserisci Colonna (Destra)";
	document.getElementById("btnIncRowSpan").title="Ingrandisci UnioneRiga";
	document.getElementById("btnDecRowSpan").title="Diminuisci UnioneRiga";
	document.getElementById("btnIncColSpan").title="Ingrandisci UnioneRiga";
	document.getElementById("btnDecColSpan").title="Diminuisci UnioneColonna";
	document.getElementById("btnDelRow").title="Rimuovi Riga";
	document.getElementById("btnDelCol").title="Rimuovi Colonna";
	document.getElementById("btnClose").value = " chiudi ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Cannot delete column.":
            return "Colonna non rimuovibile. La colonna contiene celle unite ad altre colonne. Prima bisogna rimuovere l\' unione.";
        case "Cannot delete row.":
            return "Riga non rimuovibile. La riga contiene celle unite ad altre righe. Prima bisogna rimuovere l\' unione.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Dimensione Tabella</title>")
    }