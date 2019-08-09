function loadTxt()
	{
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Trova";
	txtLang[1].innerHTML = "Sostituisci";
	txtLang[2].innerHTML = "Associa lettera";
	txtLang[3].innerHTML = "Associa parola intera";
    
    document.getElementById("btnSearch").value = "Trova un altro";;
    document.getElementById("btnReplace").value = "Sostituisci";
    document.getElementById("btnReplaceAll").value = "Scambia tutto";  
    document.getElementById("btnClose").value = "chiudi";
	}
function getTxt(s) // Needs translation
    {
    switch(s)
        {
        case "Finished searching": return "Ricerca nel documento finita.\nRicerca di nuovo dall'inizio?";
        default: return "";
        }
    }  
function writeTitle()
	{
	document.write("<title>Trova & Sostituisci</title>")
	}