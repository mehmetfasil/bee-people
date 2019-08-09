function loadTxt()
    {
    var txtLang =  document.getElementsByName("txtLang");
    //txtLang[0].innerHTML = "Size";
    txtLang[0].innerHTML = "AutoDimensiona";
    txtLang[1].innerHTML = "Propriet&agrave;";
    txtLang[2].innerHTML = "Stile";
    //txtLang[4].innerHTML = "Inserisci riga";
    //txtLang[5].innerHTML = "Inserisci colonna";
    //txtLang[6].innerHTML = "Unisci/dividi riga";
    //txtLang[7].innerHTML = "Unisci/dividi colonna";
    //txtLang[8].innerHTML = "Rimuovi Riga";
    //txtLang[9].innerHTML = "Rimuovi Colonna";    
    txtLang[3].innerHTML = "Larghezza";
    txtLang[4].innerHTML = "Auto-dimensiona in base Contenuto";
    txtLang[5].innerHTML = "Largezza Fissa";
    txtLang[6].innerHTML = "Auto-dimensiona alla videata";
    txtLang[7].innerHTML = "Altezza";
    txtLang[8].innerHTML = "Auto-dimensiona al contenuto";
    txtLang[9].innerHTML = "Altezza Fissa";
    txtLang[10].innerHTML = "Auto-dimensiona alla videata";
    txtLang[11].innerHTML = "Allineamento";
    txtLang[12].innerHTML = "Margini";
    txtLang[13].innerHTML = "Sinistra";
    txtLang[14].innerHTML = "Destra";
    txtLang[15].innerHTML = "Sopra";
    txtLang[16].innerHTML = "Sotto";
    txtLang[17].innerHTML = "Bordi";
    txtLang[18].innerHTML = "Crollo";
    txtLang[19].innerHTML = "Sfondo";
    txtLang[20].innerHTML = "Spazio Cella";
    txtLang[21].innerHTML = "Spessore Cornice";
    txtLang[22].innerHTML = "Testo CSS ";
        
    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "pixels"
    optLang[1].text = "percentuale"
    optLang[2].text = "pixels"
    optLang[3].text = "percentuale"
    optLang[4].text = "Sinistra"
    optLang[5].text = "centro"
    optLang[6].text = "destra"
    optLang[7].text = "Senza bordi"
    optLang[8].text = "Si"
    optLang[9].text = "No"

    document.getElementById("btnPick").value="Scegli";
    document.getElementById("btnImage").value="Immagine";

    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnApply").value = "applica";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Cannot delete column.":
            return "Colonna non rimuovibile. La colonna contiene celle unite ad altre colonne. Prima bisogna rimuovere l\'unione.";
        case "Cannot delete row.":
            return "Riga non rimuovibile. La riga contiene celle unite ad altre righe. Prima bisogna rimuovere l\'unione.";
        case "Custom Colors": return "Colori Personalizzati";
        case "More Colors...": return "Altri Colori...";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Proprietà Tabella</title>")
    }