function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Allineamento";
	txtLang[1].innerHTML = "Rientri";
	txtLang[2].innerHTML = "Spazio parole";
	txtLang[3].innerHTML = "Spazio caratteri";
	txtLang[4].innerHTML = "Interlinea";
	txtLang[5].innerHTML = "Maiuscolo/Minuscolo";
	txtLang[6].innerHTML = "Spazio bianco";
    
    document.getElementById("divPreview").innerHTML = "Lorem ipsum dolor sit amet, " +
        "consetetur sadipscing elitr, " +
        "sed diam nonumy eirmod tempor invidunt ut labore et " +
        "dolore magna aliquyam erat, " +
        "sed diam voluptua. At vero eos et accusam et justo " +
        "duo dolores et ea rebum. Stet clita kasd gubergren, " +
        "no sea takimata sanctus est Lorem ipsum dolor sit amet.";
    
    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Non Predisposto";
	optLang[1].text = "Sinistra";
	optLang[2].text = "Destra";
	optLang[3].text = "Centro";
	optLang[4].text = "Giustifica";
	optLang[5].text = "Non Predisposto";
	optLang[6].text = "Maiuscola iniziale";
	optLang[7].text = "Fai Maiuscolo";
	optLang[8].text = "Fai Minuscolo";
	optLang[9].text = "Nessuno";
	optLang[10].text = "Non predisposto";
	optLang[11].text = "Non Avvolgere";
	optLang[12].text = "pre";
	optLang[13].text = "Normale";
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok "; 
    }
function writeTitle()
    {
    document.write("<title>Formatta Paragrafo</title>")
    }
