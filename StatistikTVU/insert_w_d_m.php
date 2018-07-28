<?php 

include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection error</br>";}
require_once("inc/functions_display.php"); 

$is_disziplin = ($_POST['type']=="disziplin");
$is_wettkampf = ($_POST['type']=="wettkampf");
$is_mitglied = ($_POST['type']=="mitglied");


if ($is_mitglied) {
	$sql_check_existance = "SELECT ID FROM mitglied WHERE Vorname='".$_POST['vorname']."' AND Name='".$_POST['name']."' AND Jg=".$_POST['jg'];
    $result_check = $conn->query($sql_check_existance);
   // $array_result = $result_check->fetch_all(MYSQLI_ASSOC);
    if ($result_check->fetch_all(MYSQLI_ASSOC)==NULL){
        $sql="INSERT INTO mitglied VALUES ('Null','".$_POST['name']."','".$_POST['vorname']."','".$_POST['jg']."','".$_POST['sex']."','".$_POST['activity']."')";
            $result=$conn->query($sql);
            $mitglied_id = $conn->insert_id;
        if ($result == 1) {
            echo "Eingabe erfolgreich: ";
        } else {
            echo "Eingabe nicht gelungen: ";
        }
        //$sql = "SELECT ID FROM mitglied WHERE Vorname=".$_POST['vorname']." AND Name=".$_POST['name']." AND Jg=".$_POST['jg'];
    } else {
        echo "<b>Eingabe nicht geglückt. Folgende Eingabe besteht bereits: </b>";
    }
    echo $_POST['vorname']." ".$_POST['name'].", ".$_POST['jg']." ";
    echo "-----";
    echo ($mitglied_id);
}

if ($is_disziplin) {
    $sql_val_kat = $_POST['vis']['U10'].",".$_POST['vis']['U12'].",".$_POST['vis']['U14'].",".$_POST['vis']['U16'].",".$_POST['vis']['U18'].",".$_POST['vis']['U20'].",".$_POST['vis']['wom'].",".$_POST['vis']['man'];
    $sql="INSERT INTO disziplin VALUES ('Null','".$_POST['name']."','".$_POST['lauf']."','".strlen($_POST['name'])."',".$sql_val_kat.",'".$_POST['max_value']."','".$_POST['min_value']."')";
    $result=$conn->query($sql);
    if ($result == 1) {
        echo "Eingabe erfolgreich";
    } else {
        echo "Eingabe nicht gelungen";
    }
}

if ($is_wettkampf) {
    $date = new DateTime($_POST['date']);
    $year = $date->format('Y');
    ($year>1950 and $year < 2049)?
        $sql="INSERT INTO wettkampf VALUES ('Null','".$_POST['place']."','".$_POST['date']."','".$_POST['name']."',1,".$year.")" : print_r("unerwartete Jahrzahl");
    $result=$conn->query($sql);
    $wettkampf_id = $conn->insert_id;
    echo($wettkampf_id);
    if ($result == 1) {
        echo "Eingabe erfolgreich";
    } else {
        echo "Eingabe nicht gelungen";
    }

}
echo "-----";

if ($is_wettkampf) {
    visible_competitions($conn,$wettkampf_id);
}
 ?>