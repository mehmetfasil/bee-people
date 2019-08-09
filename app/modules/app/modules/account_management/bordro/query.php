<?
defined("PASS") or die("Dosya Yok!");
include (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");

switch(ACT){
    
    case "get_bordro":
        $yil = checkInput(getvalue("y"));
        $ay = checkInput(getvalue("a"));
        $type=checkInput(getvalue("t"));
        
        if($yil=="" || $ay=="" || !is_numeric($yil) || !is_numeric($ay)){
            echo "<result status='ERROR'>Bordro Ay ve yılını giriniz</result>";
            return;
        }
        if($type==0){
            //oncelikle istenen doneme ait onceki bordro bilgisi var mi bakalim        
            $control = new query("SELECT id FROM ".$table_app_emp_salaries." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and year='".$yil."' and month='".$ay."' and is_active=1 ");
            
            if($control->numrows()>0){
                //onceden olusturulmus uyari verelim.
                echo "<result status='PREVIOUS_RECORD'>".$yil." Yılı".$ay." Ay ile ilgili daha önceden bordro oluşturulmuştur.Yeniden oluşturmak ister misiniz?</result>";
                return;    
            }
        }
        if($type==1){
            //onceki bordroyu sil. yenisini yap
            new query(" DELETE FROM ".$table_app_emp_salaries." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and year='".$yil."' and month='".$ay."' and is_active=1");
        }
        
        //firmadaki kisileri cekelim.
        $get_emp = "SELECT id,emp_citizenid, emp_name,emp_surname,emp_child_count,emp_spouse_work_state,emp_marital_status FROM ".$table_app_employee." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."'";
        $s = new query($get_emp);
        if($s->numrows()>0){
            while($row=$s->fetchobject()){
                //basarili
                echo "<result status='OK'></result>";
                //her bir personel icin islem anindaki bilgilerini esas alacagiz.
                $grosswage= 0;
                $g = new query("SELECT gross_wage FROM ".$table_app_employee_accountancy." WHERE emp_id='".$row->id."' ");
                if($g->numrows()>0){
                    $gg = $g->fetchobject();
                    $grosswage = $gg->gross_wage;
                    //oncelikle bordro kaydi olusturalim. onceden olusturulan bordrolari cekelim.
                    new query("UPDATE ".$table_app_emp_salaries." SET is_active='0' WHERE emp_id='".$row->id."' and year='".$yil."' and month='".$ay."' and is_active=1 ");
                    //brutten nete hesaplayalim
                    include_once("system/classes/class.bordro.php");
                    $bordro = new  bordro($grosswage,1,$row->emp_child_count,false,$row->emp_marital_status,$row->emp_spouse_work_state,0);
                    //yeni kaydi girelim
                    $insert_salary = "INSERT INTO ".$table_app_emp_salaries." (id,emp_id,firm_id,year,month,bordro_name, insert_date,inserted_by,gross_wage,road_fee,food_cost,net_salary,worked_days,dayoff,bonus_wage,is_active)".
                    " VALUES(null,'".$row->id."', '".$_SESSION['SYS_USER_FIRM_ID']."','".$yil."','".$ay."','".$row->emp_citizenid."-".$yil."-".$ay."',NOW(),'".$_SESSION['SYS_USER_ID']."','".$grosswage."',null,null,'".$bordro->getNetMaas()."','30','0',null,'1')";
                   //$save_bordro = new query($insert_salary);
                   
                   //daha sonra evraki olusturalim ve dizine kaydedelim.
                   //dizin adi
                   $filedir = "e2box/ROOT/FIRMS/".$_SESSION['SYS_USER_FIRM_ID']."/BORDRO/".$yil."/".$ay;
                   if(!file_exists($filedir)){
                    mkdir($filedir,0777,true);
                    //o dizine index control dosyasini ekle
                    $fp=fopen($filedir.'/index.php','w');
                    fwrite($fp, '<? header("Location: http://".$_SERVER["HTTP_HOST"]); ?>');
                    fclose($fp);
                   }
                   ob_start();
                   require('objects/libraries/TCPDF/tcpdf_include.php');
                   
                   // create new PDF document
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'utf-8', false);
                    
                    // set document information
                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetFont('helvetica',"","","","","");
                    $pdf->SetAuthor('E2');
                    $pdf->SetTitle('E2 Bordro');
                    $pdf->SetSubject('E2 Bordro');
                    // set default header data
                    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
                    // set auto page breaks
                    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                    // set image scale factor
                    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                    // add a page
                    $pdf->AddPage();
                    $html = '<div style="font-size:12px;font-family:arial">
	<div style="background-color:#2c4370;color:#fff;width:100%;height:200px;">
		<div style="width:50%;float:left;">
			<img src="templates/app/images/logo.png"/>
		</div>
		<div style="width:50%;float:left;">
			<h3>Maas Bordrosu</h3>
			<h4>(Kasim 2016)</h4>
		</div>
		<div style="clear:both"></div>
	</div>
	<div style="margin-top: 40px;">
		<div style="float:left;width:45%;border:2px #cae0ed solid;margin-left:50px;margin-right:30px">
			<div style="background-color:#2c436f"><h3 style="margin:0px;padding:10px;color:white">Firma Bilgileri</h3></div>
			<table width="100%">
                <tr>
                    <td colspan="2"><b>İşverenin;</b></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td><b>Unvanı:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Adresi:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Vergi No:</b></td>
                    <td></td>
                </tr>
			</table>
		</div>
		<div style="float:left;width:45%;border:2px #cae0ed solid;">
			<div style="background-color:#2c436f"><h3 style="margin:0px;padding:10px;color:white">Firma Bilgileri</h3></div>
			<table width="100%">
                <tr>
                    <td colspan="2"><b>Bordro Sahibinin;</b></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td><b>Kimlik Numarası:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Adı Soyadı:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>İşe Başlama Tarihi:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Yıl:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Ay:</b></td>
                    <td></td>
                </tr>
			</table>
		</div>
		<div style="clear:both"></div>
	</div>
    <div style="margin-top: 40px;">
		<div style="float:left;width:45%;border:2px #cae0ed solid;margin-left:50px;margin-right:30px;">
			<div style="background-color:#2c436f"><h3 style="margin:0px;padding:10px;color:white">Kazançlar</h3></div>
			<table width="100%">
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td><b>Normal Gün:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Hafta Tatili:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Resmi Tatil:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Toplam Gün:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Brüt Ücret:</b></td>
                    <td></td>
                </tr>
			</table>
		</div>
		<div style="float:left;width:45%;border:2px #cae0ed solid;">
			<div style="background-color:#2c436f"><h3 style="margin:0px;padding:10px;color:white">Kesintiler</h3></div>
			<table width="100%">
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td><b>SSK Matrahı:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Çalışma Günü:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>SSK Primi:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>İşsizlik Kesintisi:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Gelir Vergisi:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Asgari Gelir İndirimi:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Damga Vergisi:</b></td>
                    <td></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td><b>Toplam Kesinti:</b></td>
                    <td></td>
                </tr>
			</table>
		</div>
		<div style="clear:both"></div>
	</div>
    <div style="background: #2c436f;margin:3%;">
        <table width="100%" style="color:white;padding:3%">
            <tr>
                <td align="center"><h3>Brüt Maaş</h3></td>
                <td align="center"><h3>Toplam Kesinti</h3></td>
                <td align="center"><h3>Net Maaş</h3></td>
            </tr>
        </table>
    </div>
    <div align="center">
        <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=http%3A%2F%2Fwww.google.com%2F&choe=UTF-8" title="Link to Google.com" />
    </div>
    <div style="color: #ebebeb;" align="center">
        <p>E2 tarafından otomatik olarak oluşturulmuştur.</p>
        <p>Oluşturma Tarihi: 22/11/2016 Saat:09:57</p>
    </div>
</div>';
                    $pdf->writeHTML($html, true, false, true, false, '');
                    $filePath = CONF_DOCUMENT_ROOT."e2box/ROOT/FIRMS/".$_SESSION['SYS_USER_FIRM_ID']."/BORDRO/".$yil."/".$ay."/".$yil."-".$ay.$row->emp_citizenid.".pdf";
                    $file = "e2box/ROOT/FIRMS/".$_SESSION['SYS_USER_FIRM_ID']."/BORDRO/".$yil."/".$ay."/".$yil."-".$ay.$row->emp_citizenid.".pdf";
                    $pdf->Output($filePath, 'F');
                    ob_end_clean();
                    //ekranda gorunmesi icin yazdiralim.
                    echo "<bordro ci='".$row->emp_citizenid."' ei='".$row->id."' ename='".$row->emp_name." ".$row->emp_surname."' gw='".$grosswage."' ns='".$bordro->getNetMaas()."' b='".$file."'></bordro>\n";
                    
                }else{
                    //personelin maas bilgisi yok. hesaplanamadi
                    echo "<report status='ERROR'>".$row->emp_citizenid." Kimlik Nolu Personelin Maaş Kaydı Bilgisi Bulunamadı</report>";
                }
            }
        }else{
                 echo "<result status='ERROR'>Personelin Maaş Kaydı Bilgisi Bulunamadı</result>";
            }
        
    break;

}
?>