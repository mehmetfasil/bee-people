<?
defined("PASS") or die("Dosya Yok!");
include(CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");

switch(ACT){
    case "pp":
    /*
    [puantaj_start] => 02/12/2016
    [puantaj_end] => 02/12/2016
    */
    
    $start_date = getvalue("puantaj_start");
    $end_date = getvalue("puantaj_end");
    
    if($start_date=="" || $start_date==null || !isDate($start_date)){
        echo "<result status='ERROR'>Başlangıç Tarihi Hatalı</result>";
        return;
    }
    if($end_date=="" || $end_date==null || !isDate($end_date)){
        echo "<result status='ERROR'>Bitiş Tarihi Hatalı</result>";
        return;
    }
    
    $user_firm_id = $_SESSION['SYS_USER_FIRM_ID'];
    
    $start_date = convertToMysqlDateFormat($start_date);
    $end_date = convertToMysqlDateFormat($end_date);
    
    if($end_date<$start_date){
        echo "<result status='ERROR'>Bitiş Tarihi Başlangıç Tarihinden Büyük Olmalıdır</result>";
        return;
    }
    
    //calisanlari getirelim.
    $get = "SELECT id,emp_name,emp_surname FROM ".$table_app_employee." WHERE firm_id='".$user_firm_id."' ";
    $select = new query($get);
    if($select->numrows()>0){
        echo "<result status='OK'></result>";
        $personel = array();
        $daysAllDays = array();
        $tempStart = $start_date;
        $tempEnd = $end_date;
            while (strtotime($tempStart) <= strtotime($tempEnd)) {
                $arrayAdd = array();
                $dayNum = strtolower(date("d",strtotime($tempStart)));
                $arrayAdd["dayNum"]=$dayNum;
                $sepparator = '-';
                $parts = explode($sepparator, $tempStart);
                $dayForDate = date("N", mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]));
                $arrayAdd["shortName"]=$TurkceGunKisa[$dayForDate];
                $tempStart = date ("Y-m-d", strtotime("+1 day", strtotime($tempStart)));
                array_push($daysAllDays,$arrayAdd);
            }
        
        $n = 0;
        while($row=$select->fetchobject()){
            
            $personel[$n]["empname"] = $row->emp_name." ".$row->emp_surname;
            $AllDays = array();
            $tempStart = $start_date;
            $tempEnd = $end_date;
            while (strtotime($tempStart) <= strtotime($tempEnd)) {
                $AllDays[$tempStart]="D";
                $dayNum = strtolower(date("N",strtotime($tempStart)));
                if($dayNum>=6){
                  $AllDays[$tempStart]="T";  
                }else{
                    $AllDays[$tempStart]="D";
                }
                $tempStart = date ("Y-m-d", strtotime("+1 day", strtotime($tempStart)));
            }
            //herbirpersonelicin belirtilen tarih araliginda izin, mazeret vb. var mı bakalım.
            $get_dayoff = "select start_date,end_date from ".$table_app_employee_dayoff." where 
            ((start_date>'".$start_date."' and start_date<'".$end_date."') or
            (end_date>'".$start_date."' and end_date<'".$end_date."') or
            (start_date<'".$start_date."' and end_date>'".$end_date."'))
             and emp_id='".$row->id."'  and is_approved=1 ";
            $s = new query($get_dayoff);
            if($s->numrows()>0){
                while($r=$s->fetchobject()){
                    $s_date = $r->start_date;
                    $f_date = $r->end_date;
                    $restDays = GetDays($s_date,$f_date);
                    foreach($restDays as $k=>$v){
                     if(in_array($v,$restDays)){
                            //izinli gun
                            $AllDays[$v]="Y";
                        }   
                    }
                }
            }
            $personel[$n]["days"] = $AllDays;
            $n++;       
        }
        
        foreach($daysAllDays as $k=>$v){
          echo "<days num='".$v["dayNum"]."' shortname='".$v["shortName"]."'></days>\n";
        }
        foreach($personel as $k=>$v){
            echo "<emp name='".$v["empname"]."'>\n";
            foreach($v["days"] as $k=>$t){
                echo "<d>".$t."</d>\n";
            }
            echo "</emp>\n";
        }
        
    }else{
        echo "<result status='NR'>Personel Kaydı Bulunamadı.</result>";
    }
    
    break;
}

?>