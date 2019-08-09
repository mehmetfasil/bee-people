function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
	txtLang[0].innerHTML = "Fonte";
	txtLang[1].innerHTML = "Titolo";	
	txtLang[2].innerHTML = "Spazio";
	txtLang[3].innerHTML = "Allineamento";	
	txtLang[4].innerHTML = "Soprastante";
	txtLang[5].innerHTML = "Bordo";	
	txtLang[6].innerHTML = "Sottostante";
	txtLang[7].innerHTML = "Larghezza";	
	txtLang[8].innerHTML = "Sinistra";
	txtLang[9].innerHTML = "Altezza";		
	txtLang[10].innerHTML = "Destra";	
    
    var optLang = document.getElementsByName("optLang");
	optLang[0].text = "Estremo Sotto"
	optLang[1].text = "Estremo Centro"
	optLang[2].text = "Linea base"
	optLang[3].text = "Sottostante"
	optLang[4].text = "Sinistra"
	optLang[5].text = "Centro"
	optLang[6].text = "Destra"
	optLang[7].text = "Testo Soprastante"
	optLang[8].text = "Soprastante"
 
    document.getElementById("btnBorder").value = " Stile Bordo ";
    document.getElementById("btnReset").value = "cambia"
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnInsert").value = "inserisci";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Immagine</title>")
    }