function loadTxt()
    {
    document.getElementById("txtLang").innerHTML = "Kleur";
    document.getElementById("btnCancel").value = "annuleren";
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
        case "More Colors...": return "Eigen kleuren...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Randen</title>")
    }