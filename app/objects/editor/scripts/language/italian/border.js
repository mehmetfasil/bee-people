function loadTxt()
    {
    document.getElementById("txtLang").innerHTML = "Colore";
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
	{
	switch(s)
		{
		case "No Border": return "Senza Bordo";
		case "Outside Border": return "Bordo esterno";  
		case "Left Border": return "Bordo a sinistra";
		case "Top Border": return "Bordo superiore";
		case "Right Border": return "Bordo a destra";
		case "Bottom Border": return "Bordo inferiore";
		case "Pick": return "Scegli";
		case "Custom Colors": return "Colori Personalizzati";
		case "More Colors...": return "Altri Colori..";
		default: return "";
		}
	}
function writeTitle()
	{
	document.write("<title>Bordi</title>")
	}