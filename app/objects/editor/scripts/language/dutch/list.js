function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Genummerd";
    txtLang[1].innerHTML = "Opsommingstekens";
    txtLang[2].innerHTML = "Start nummer";
    txtLang[3].innerHTML = "Marge links";
    txtLang[4].innerHTML = "Afbeelding url gebruiken"
    txtLang[5].innerHTML = "Marge links";
    
    document.getElementById("btnCancel").value = "annuleren";
    document.getElementById("btnApply").value = "toepassen";
    document.getElementById("btnOk").value = " ok ";   
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Please select a list.":return "Kies een lijst.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Lijst Opmaak</title>")
    }
