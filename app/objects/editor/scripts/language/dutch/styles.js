function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Stijlen";
    txtLang[1].innerHTML = "Voorbeeld";
    txtLang[2].innerHTML = "Toepassen op";

    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Geselecteerde tekst"
    optLang[1].text = "Huidige Element"
    
    document.getElementById("btnCancel").value = "annuleren";
    document.getElementById("btnApply").value = "toepassen";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "You're selecting BODY element.":
            return "U selecteert het BODY element.";
        case "Please select a text.":
            return "Selecteer een tekst.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Stijlen</title>")
    }