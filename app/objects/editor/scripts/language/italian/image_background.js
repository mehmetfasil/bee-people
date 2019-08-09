function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Fonte Immagine";
	txtLang[1].innerHTML = "Ripeti";
	txtLang[2].innerHTML = "Allinea orizzontale";
	txtLang[3].innerHTML = "Allinea verticale";

    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Ripeti"
	optLang[1].text = "Non Ripetere"
	optLang[2].text = "Ripeti orizzontale"
	optLang[3].text = "Ripeti verticale"
	optLang[4].text = "sinistra"
	optLang[5].text = "centro"
	optLang[6].text = "destra"
	optLang[7].text = "pixels"
	optLang[8].text = "percentuale"
	optLang[9].text = "soprastante"
	optLang[10].text = "centro"
	optLang[11].text = "sottostante"
	optLang[12].text = "pixels"
	optLang[13].text = "percentuale"
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Sfondo Immagine</title>")
    }

