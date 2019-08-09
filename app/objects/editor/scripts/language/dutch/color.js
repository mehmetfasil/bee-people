function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Web pallet";
    txtLang[1].innerHTML = "Benoemde kleuren";
    txtLang[2].innerHTML = "216 Web veilig";
    txtLang[3].innerHTML = "Nieuw";
    txtLang[4].innerHTML = "Huidig";
    txtLang[5].innerHTML = "Eigen kleuren";
    
    document.getElementById("btnAddToCustom").value = "Toevoegen aan Eigen kleuren";
    document.getElementById("btnCancel").value = " annuleren ";
    document.getElementById("btnRemove").value = " verwijder kleur ";
    document.getElementById("btnApply").value = " toepassen ";
    document.getElementById("btnOk").value = " ok ";
    }
function writeTitle()
    {
    document.write("<title>Kleuren</title>")
    }