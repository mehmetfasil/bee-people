function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Bron";
    txtLang[1].innerHTML = "Breedte";
    txtLang[2].innerHTML = "Hoogte";    
    txtLang[3].innerHTML = "Auto start";    
    txtLang[4].innerHTML = "Toon knoppen";
    txtLang[5].innerHTML = "Toon status balk";   
    txtLang[6].innerHTML = "Toon beeld";
    txtLang[7].innerHTML = "Auto terugspoelen";   

    document.getElementById("btnCancel").value = "annuleren";
    document.getElementById("btnInsert").value = "invoegen";
    document.getElementById("btnApply").value = "toepassen";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Media</title>")
    }
