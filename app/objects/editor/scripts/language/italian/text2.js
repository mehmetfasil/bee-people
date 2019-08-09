var sStyleWeight1;
var sStyleWeight2;
var sStyleWeight3;
var sStyleWeight4; 

function loadTxt()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Carattere";
    txtLang[1].innerHTML = "Stile";
    txtLang[2].innerHTML = "Dimensione";
    txtLang[3].innerHTML = "Testo";
    txtLang[4].innerHTML = "Sfondo";

    txtLang[5].innerHTML = "Decorazione";
    txtLang[6].innerHTML = "Maiuscolo/Minuscolo";
    txtLang[7].innerHTML = "Maiuscole Micro";
    txtLang[8].innerHTML = "Verticale";

    txtLang[9].innerHTML = "Non Definito";
    txtLang[10].innerHTML = "Sottolineato";
    txtLang[11].innerHTML = "Sopralineato";
    txtLang[12].innerHTML = "Sbarrato";
    txtLang[13].innerHTML = "Nessuno";

    txtLang[14].innerHTML = "Non Definito";
    txtLang[15].innerHTML = "Maiuscola Iniziale";
    txtLang[16].innerHTML = "Fai Maiuscolo";
    txtLang[17].innerHTML = "Fai Minuscolo";
    txtLang[18].innerHTML = "Nessuno";

    txtLang[19].innerHTML = "Non Definito";
    txtLang[20].innerHTML = "Maiuscole Micro";
    txtLang[21].innerHTML = "Normale";

    txtLang[22].innerHTML = "Non Definito";
    txtLang[23].innerHTML = "Sopra interlinea";
    txtLang[24].innerHTML = "Sotto interlinea";
    txtLang[25].innerHTML = "Relativo";
    txtLang[26].innerHTML = "Base interlinea";
    
    txtLang[27].innerHTML = "Spazio Caratteri";
    
    var optLang = document.getElementsByName("optLang");
    optLang[0].text = "Regolare"
    optLang[1].text = "Italico"
    optLang[2].text = "Grossetto"
    optLang[3].text = "Italico Grossetto"

    optLang[0].value = "Regolare"
    optLang[1].value = "Italico"
    optLang[2].value = "Grossetto"
    optLang[3].value = "Italico Grossetto"
    
    sStyleWeight1 = "Regolare"
    sStyleWeight2 = "Italico"
    sStyleWeight3 = "Grossetto"
    sStyleWeight4 = "Italico Grossetto"
    
    optLang[4].text = "Sopra"
    optLang[5].text = "Mezzo"
    optLang[6].text = "Sotto"
    optLang[7].text = "Testo-Sopra"
    optLang[8].text = "Testo-Sotto"
    
    document.getElementById("btnPick1").value = "Scegli";
    document.getElementById("btnPick2").value = "Scegli";

    document.getElementById("btnCancel").value = "cancella";
    document.getElementById("btnOk").value = " ok ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Custom Colors": return "Colori Personalizzati";
        case "More Colors...": return "Altri Colori...";
        default: return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Formata Testo</title>")
    }