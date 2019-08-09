<?
defined("PASS") or die("Dosya yok!");
header("Content-type: text/xml; charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
echo "<content type=\"system\">";


include_once (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
switch (ACT) {
    
    
    case "dismiss_types":
        $sql = " SELECT id,name FROM ".$table_app_f_lookups. " WHERE (type='2' and is_general='1') or (type='2' and is_general='0' and firm_id='".$_SESSION['SYS_USER_FIRM_ID']."') ";
        $select = new query($sql);
        //getrows
        while ($row = $select->fetchobject()) {
            echo "<item id='" . $row->id . "' name='" . $row->name . "'></item>";
        }
    break;
    
    
    case "dayoff_types":
        $sql_select_dayoff_types = "SELECT id,name FROM " . $table_app_f_lookups .
            " where is_active=1 and ((type=4 and firm_id='".$_SESSION['SYS_USER_FIRM_ID']."') or (type=4 and is_general='1')) ";
        $select = new query($sql_select_dayoff_types);
        //getrows
        while ($row = $select->fetchobject()) {
            echo "<item id='" . $row->id . "' name='" . $row->name . "'></item>";
        }
        break;
    break;
    
    case "get_roles":
        $sql_select_app_roles = "SELECT id,role_name FROM " . $table_app_roles .
            " where id!=3 ";
        $select_app_roles = new query($sql_select_app_roles);
        //getrows
        while ($row = $select_app_roles->fetchobject()) {
            echo "<item id='" . $row->id . "' name='" . $row->role_name . "'></item>";
        }
        break;

    default:
        break;

    case "get_counties":

        require "esb/predis/autoloader.php";
        Predis\Autoloader::register();
        $redis = new Predis\Client();
        try {
            $redis = new Predis\Client(array(
                "scheme" => "tcp",
                "host" => "127.0.0.1",
                "port" => 6379));
            if ($redis) {
                $counties = $redis->get('counties');

                if ($counties == null) {
                    $key = array();
                    $sql_select_l_county = "SELECT id,isim FROM " . $table_l_county .
                        " WHERE durum=1";
                    $select_l_county = new query($sql_select_l_county);
                    while ($row = $select_l_county->fetchobject()) {
                        $key[$row->id] = $row->isim;
                    }
                    $put = json_encode($key);
                    $redis->set("counties", $put);
                } else {
                    $decode = json_decode($counties);
                    foreach ($decode as $k => $v) {
                        echo "<item id='" . $k . "' name='" . $v . "'></item>";
                    }

                }
            }
        }
        catch (exception $e) {
            $sql_select_l_county = "SELECT id,isim FROM " . $table_l_county .
                " WHERE durum=1";
            $select_l_county = new query($sql_select_l_county);
            while ($row = $select_l_county->fetchobject()) {
                echo "<item id='" . $row->id . "' name='" . $row->isim . "'></item>";
            }
        }


        break;

    case "get_towns":

        $sql_select_l_towns = "SELECT id,isim FROM " . $table_l_town . " WHERE il=" . ID;
        $select_l_town = new query($sql_select_l_towns);
        while ($row = $select_l_town->fetchobject()) {
            echo "<item id='" . $row->id . "' name='" . $row->isim . "'></item>";
        }

        break;

    case "get_states":
        $sql_select_states = "SELECT id, isim FROM " . $table_l_states;
        $select_l_states = new query($sql_select_states);
        while ($row = $select_l_states->fetchobject()) {
            echo "<item id='" . $row->id . "' name='" . $row->isim . "'></item>";
        }
        break;

    case "get_ftypes":
        $sql_select_ft = "SELECT id, file_name FROM " . $table_app_file_definition .
            " WHERE is_active=1 and ((firm_id='" . $_SESSION['SYS_USER_FIRM_ID'] . "' and is_general='0') or (is_general='1' and firm_id='0')) ";
        $select_ft = new query($sql_select_ft);
        while ($row = $select_ft->fetchobject()) {
            echo "<item id='" . $row->id . "' name='" . $row->file_name . "'></item>";
        }
        break;

    case "delete_emp_file":
        $file_id = checkInput(getvalue("fid"));
        $emp_id = checkInput(getvalue("e_id"));
        $file_type = checkInput(getvalue("f_type"));
        if (!empty($file_id)) {
            //TODO: how to check is auth????
            return;
        }

        if ($emp_id != "" && $file_type != "") {
            if (!isAuthorized("", $emp_id, "")) {
                echo "<result status='ERROR'>Yetkisiz Eriþim</result>";

            } else {
                $del = "UPDATE " . $table_app_employee_files . " SET is_active=0 where emp_id='" .
                    $emp_id . "' AND app_file_id='" . $file_type . "'";
                $q = new query($del);
                if ($q->affectedrows() >= 0) {
                    echo "<result status='OK'></result>";

                } else {
                    echo "<result status='ERROR'>Hata Oluþtu!</result>";

                }
            }

        }


        break;
}
echo "</content>";
?>