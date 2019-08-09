<?
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    die("Dosya Yok!");
}
defined("PASS") or die("Dosya Yok!");
include_once (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
if (isset($_SESSION['SYS_USER_ID']) && $_SESSION['SYS_USER_ID'] > 0) {

    if (isset($_FILES['toplu_islem'])) {
        $errors = array();
        $file_name = $_FILES['toplu_islem']['name'];
        $file_name_u = rand(1, 999);
        $file_size = $_FILES['toplu_islem']['size'];
        $file_tmp = $_FILES['toplu_islem']['tmp_name'];
        $file_type = $_FILES['toplu_islem']['type'];
        $file_ext = substr(strrchr($_FILES['toplu_islem']['name'], '.'), 1);
        $expensions = array("csv");

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "Dosyanın uzantısı csv olmalıdır. ";
        }

        if ($file_size > 2097152) {
            $errors[] = 'Dosya boyutu 2 MBdan fazla olamaz.';
        }

        if (empty($errors) == true) {
            $rand = trim(getGUID(), "{}");

            $path = 'e2box/ROOT/BULK_INSERT/' . $rand . '/' . $_SESSION['SYS_USER_FIRM_ID'] .
                '/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fullPath = $path . $file_name_u . '.' . $file_ext;
            if (move_uploaded_file($file_tmp, $fullPath)) {
                //uploaded. handle it.
                if (($handle = fopen($fullPath, "r")) !== false) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                        $num = count($data);
                        echo "<p> $num fields in line $row: <br /></p>\n";
                        $row++;
                        for ($c = 0; $c < $num; $c++) {
                            $blackpowder = $data;
                            $dynamit = implode(";", $blackpowder);
                            $pieces = explode(";", $dynamit);
                            $col1 = $pieces[0];
                            $col2 = $pieces[1];
                            $col3 = $pieces[2];
                            $col4 = $pieces[3];
                            $col5 = $pieces[5];
                        }
                    }
                }
            }
        } else {
            echo $errors[0];
        }
    } else {
        echo "Lütfen excel dosyasını yükleyiniz";
    }
}

?>
