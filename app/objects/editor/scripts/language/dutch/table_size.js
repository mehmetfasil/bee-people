function loadTxt()
    {
    var txtLang =  document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "Rij Invoegen";
    txtLang[1].innerHTML = "Kolom Invoegen";
    txtLang[2].innerHTML = "Rij Samenvoegen";
    txtLang[3].innerHTML = "Kolom Samenvoegen";
    txtLang[4].innerHTML = "Rij Verwijderen";
    txtLang[5].innerHTML = "Kolom Verwijderen";

	document.getElementById("btnInsRowAbove").title="Rij Invoegen (Boven)";
	document.getElementById("btnInsRowBelow").title="Rij Invoegen (Onder)";
	document.getElementById("btnInsColLeft").title="Kolom Invoegen (Links)";
	document.getElementById("btnInsColRight").title="Kolom Invoegen (Rechts)";
	document.getElementById("btnIncRowSpan").title="Increase Rowspan";
	document.getElementById("btnDecRowSpan").title="Decrease Rowspan";
	document.getElementById("btnIncColSpan").title="Increase Colspan";
	document.getElementById("btnDecColSpan").title="Decrease Colspan";
	document.getElementById("btnDelRow").title="Rij Verwijderen";
	document.getElementById("btnDelCol").title="Kolom Verwijderen";

	document.getElementById("btnClose").value = " sluiten ";
    }
function getTxt(s)
    {
    switch(s)
        {
        case "Cannot delete column.":
            return "De kolom kan niet verwijderd worden. De kolom bevat samengevoegde cellen van een andere kolom. Verwijder eerst de samenvoeging.";
        case "Cannot delete row.":
            return "De rij kan niet verwijderd worden. De rij bevat samengevoegde cellen van een andere rioj. Verwijder eerst de samenvoeging.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Grootte</title>")
    }