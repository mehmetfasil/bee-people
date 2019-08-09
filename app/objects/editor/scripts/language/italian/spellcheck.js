function loadTxt()
	{
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnOk").value = " ok ";
	}
function getTxt(s)
	{
	switch(s)
		{
		case "Required":
			return "ieSpell (da www.iespell.com) è richiesto.";
		default:return "";
		}
	}
function writeTitle()
	{
	document.write("<title>Controllo Ortografico</title>")
	}