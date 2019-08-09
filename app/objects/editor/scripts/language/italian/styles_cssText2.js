function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Anteprima";
    txtLang[1].innerHTML = "CSS Testo";
    txtLang[2].innerHTML = "Nome Classe";

    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
		case "You're selecting BODY element.":
			return "Stai selezionando un elemento del corpo del testo.";
		case "Please select a text.":
			return "Prego selezionare un testo.";
		default:return ""
        }
    }
function writeTitle()
    {
    document.write("<title>Personalizza CSS</title>")
    }