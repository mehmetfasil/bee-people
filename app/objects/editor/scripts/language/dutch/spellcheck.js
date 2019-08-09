function loadTxt()
	{
	document.getElementById("btnCheckAgain").value = " Opnieuw Controleren ";
    document.getElementById("btnCancel").value = "annuleren";
    document.getElementById("btnOk").value = " ok ";
	}
function getTxt(s)
	{
	switch(s)
		{
		case "Required":
			return "ieSpell (from www.iespell.com) is benodigd.";
		default:return "";
		}
	}
function writeTitle()
	{
	document.write("<title>Spellings Controle</title>")
	}