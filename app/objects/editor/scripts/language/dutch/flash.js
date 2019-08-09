function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Bron";
    txtLang[1].innerHTML = "Achtergrond";
    txtLang[2].innerHTML = "Breedte";
    txtLang[3].innerHTML = "Hoogte";
    txtLang[4].innerHTML = "Kwaliteit";
    txtLang[5].innerHTML = "Uitlijning";
    txtLang[6].innerHTML = "Herhaling";
    txtLang[7].innerHTML = "Ja";
    txtLang[8].innerHTML = "Nee";
    
    txtLang[9].innerHTML = "Class ID";
    txtLang[10].innerHTML = "CodeBase";
    txtLang[11].innerHTML = "PluginsPage";

    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Laag"
    optLang[1].text = "Hoog"
    optLang[2].text = "<Not Set>"
    optLang[3].text = "Links"
    optLang[4].text = "Rechts"
    optLang[5].text = "Boven"
    optLang[6].text = "Onder"
    
    document.getElementById("btnPick").value = "Kiezen";
    
    document.getElementById("btnCancel").value = "annuleren";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Custom Colors": return "Eigen kleuren";
        case "More Colors...": return "Eigen kleuren...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Flash Invoegen</title>")
    }