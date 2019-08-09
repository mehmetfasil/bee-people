function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Colore";
    txtLang[1].innerHTML = "Tonalit&agrave;";   
    
    txtLang[2].innerHTML = "Margine";
    txtLang[3].innerHTML = "Sinistra";
    txtLang[4].innerHTML = "Destra";
    txtLang[5].innerHTML = "Superiore";
    txtLang[6].innerHTML = "Inferiore";
    
    txtLang[7].innerHTML = "Spessore Cornice";
    txtLang[8].innerHTML = "Sinistra";
    txtLang[9].innerHTML = "Destra";
    txtLang[10].innerHTML = "Superiore";
    txtLang[11].innerHTML = "Inferiore";
    
    txtLang[12].innerHTML = "Dimensioni";
    txtLang[13].innerHTML = "Larghezza";
    txtLang[14].innerHTML = "Altezza";
    
    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "pixels";
    optLang[1].text = "percentuale";
    optLang[2].text = "pixels";
    optLang[3].text = "percentuale";
    
    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnApply").value = "applica a";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "No Border": return "Senza Bordo";
        case "Outside Border": return "Bordo esterno";
        case "Left Border": return "Bordo sinistra";
        case "Top Border": return "Bordo superiore";
        case "Right Border": return "Bordo destra";
        case "Bottom Border": return "Bordo inferiore";
        case "Pick": return "Scegli";
        case "Custom Colors": return "Colori Personalizzati";
        case "More Colors...": return "Altri Colori..";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Formatta casella</title>")
    }