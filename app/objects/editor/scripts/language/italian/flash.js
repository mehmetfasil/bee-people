function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Fonte";
	txtLang[1].innerHTML = "Sfondo";
	txtLang[2].innerHTML = "Larghezza";
	txtLang[3].innerHTML = "Altezza";
	txtLang[4].innerHTML = "Qualita";	
	txtLang[5].innerHTML = "Allinea";
	txtLang[6].innerHTML = "Reiterare";
	txtLang[7].innerHTML = "Si";
	txtLang[8].innerHTML = "No";
    
    txtLang[9].innerHTML = "Classe ID";
	txtLang[10].innerHTML = "CodeBase";
	txtLang[11].innerHTML = "PluginsPage";

    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Basso"
	optLang[1].text = "Alto"
	optLang[2].text = "<Non Predisposto>"
	optLang[3].text = "Sinistra"
	optLang[4].text = "Destra"
	optLang[5].text = "Sopra"
	optLang[6].text = "Sotto"
    
    document.getElementById("btnPick").value = "Scegli";
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Custom Colors": return "Colori Personalizzati";
        case "More Colors...": return "Altri Colori..";
        default: return "";
        }
    }    
function writeTitle()
    {
    document.write("<title>Inserisci Flash</title>")
    }