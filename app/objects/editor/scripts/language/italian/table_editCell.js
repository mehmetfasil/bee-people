function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Autodimensiona";
	txtLang[1].innerHTML = "Propriet&agrave;";
	txtLang[2].innerHTML = "Stile";
	txtLang[3].innerHTML = "Larghezza";
	txtLang[4].innerHTML = "Adatta al contenuto";
	txtLang[5].innerHTML = "Largezza fissa celle";
	txtLang[6].innerHTML = "Altezza";
	txtLang[7].innerHTML = "Adatta al contenuto";
	txtLang[8].innerHTML = "Altezza fissa celle";
	txtLang[9].innerHTML = "Allineamento Testo";
	txtLang[10].innerHTML = "Spessore Cornice";
	txtLang[11].innerHTML = "Sinistra";
	txtLang[12].innerHTML = "Destra";
	txtLang[13].innerHTML = "Sopra";
	txtLang[14].innerHTML = "Sotto";
	txtLang[15].innerHTML = "Spazio bianco";
	txtLang[16].innerHTML = "Sfondo";
	txtLang[17].innerHTML = "Anteprima";
	txtLang[18].innerHTML = "Testo CSS";
	txtLang[19].innerHTML = "Applica";

    document.getElementById("btnPick").value = "Scegli";
    document.getElementById("btnImage").value = "Immagine";
    document.getElementById("btnText").value = " Formato Testo ";
    document.getElementById("btnBorder").value = " Stile Bordi ";

    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    
    var optLang = document.getElementsByName("optLang");
   	optLang[0].text = "pixels"
	optLang[1].text = "percentuale"
	optLang[2].text = "pixels"
	optLang[3].text = "percentuale"
	optLang[4].text = "non predisposto"
	optLang[5].text = "soprastante"
	optLang[6].text = "centro"
	optLang[7].text = "sottostante"
	optLang[8].text = "linea base"
	optLang[9].text = "Sub"
	optLang[10].text = "super"
	optLang[11].text = "testo-sopra"
	optLang[12].text = "testo-sotto"
	optLang[13].text = "non predisposto"
	optLang[14].text = "sinistra"
	optLang[15].text = "centro"
	optLang[16].text = "destra"
	optLang[17].text = "giustifica"
	optLang[18].text = "non predisposto"
	optLang[19].text = "Non avvolgere"
	optLang[20].text = "pre"
	optLang[21].text = "Normale"
	optLang[22].text = "Cella corrente"
	optLang[23].text = "Riga corrente"
	optLang[24].text = "Colonna corrente"
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Custom Colors": return "Personalizza Colori";
        case "More Colors...": return "Altri Colori..";
        default: return "";
        }
    }    
function writeTitle()
    {
    document.write("<title>Proprieta Cella</title>")
    }