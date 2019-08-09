function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Uitlijning";
    txtLang[1].innerHTML = "Inspringing";
    txtLang[2].innerHTML = "Woord ruimte";
    txtLang[3].innerHTML = "Karakter ruimte";
    txtLang[4].innerHTML = "Lijnhoogte";
    txtLang[5].innerHTML = "Tekst stand";
    txtLang[6].innerHTML = "Wit ruimte";
    
    document.getElementById("divPreview").innerHTML = "Lorem ipsum dolor sit amet, " +
        "consetetur sadipscing elitr, " +
        "sed diam nonumy eirmod tempor invidunt ut labore et " +
        "dolore magna aliquyam erat, " +
        "sed diam voluptua. At vero eos et accusam et justo " +
        "duo dolores et ea rebum. Stet clita kasd gubergren, " +
        "no sea takimata sanctus est Lorem ipsum dolor sit amet.";
    
    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Niet gebruikt";
    optLang[1].text = "Links";
    optLang[2].text = "Rechts";
    optLang[3].text = "Midden";
    optLang[4].text = "Uitgelijnd";
    optLang[5].text = "Niet gebruikt";
    optLang[6].text = "Kapitaal";
    optLang[7].text = "Hoofdletters";
    optLang[8].text = "Kleine letters";
    optLang[9].text = "Geen";
    optLang[10].text = "Niet gebruikt";
    optLang[11].text = "Geen terugloop";
    optLang[12].text = "pre";
    optLang[13].text = "Normaal";
    
    document.getElementById("btnCancel").value = "annuleren";
    document.getElementById("btnApply").value = "toepassen";
    document.getElementById("btnOk").value = " ok ";   
    }
function writeTitle()
    {
    document.write("<title>Paragraaf Opmaak</title>")
    }
