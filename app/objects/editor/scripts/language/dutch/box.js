function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Kleur";
    txtLang[1].innerHTML = "Schaduw";   
    
    txtLang[2].innerHTML = "Vrije ruimte";
    txtLang[3].innerHTML = "Links";
    txtLang[4].innerHTML = "Rechts";
    txtLang[5].innerHTML = "Boven";
    txtLang[6].innerHTML = "Onder";
    
    txtLang[7].innerHTML = "Opvulling";
    txtLang[8].innerHTML = "Links";
    txtLang[9].innerHTML = "Rechts";
    txtLang[10].innerHTML = "Boven";
    txtLang[11].innerHTML = "Onder";

    txtLang[12].innerHTML = "Grootte";
    txtLang[13].innerHTML = "Breedte";
    txtLang[14].innerHTML = "Hoogte";
    
    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "pixels";
    optLang[1].text = "procent";
    optLang[2].text = "pixels";
    optLang[3].text = "procent";
        
    document.getElementById("btnCancel").value = "annuleren";
    document.getElementById("btnApply").value = "toepassen";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "No Border": return "Geen rand";
        case "Outside Border": return "Rand buiten";
        case "Left Border": return "Rand links";
        case "Top Border": return "Rand boven";
        case "Right Border": return "Rand rechts";
        case "Bottom Border": return "Rand onder";
        case "Pick": return "Kiezen";
        case "Custom Colors": return "Eigen kleuren";
        case "More Colors...": return "Meer kleuren...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Kader opmaak</title>")
    }