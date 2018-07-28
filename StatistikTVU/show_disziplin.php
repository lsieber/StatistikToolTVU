<?php

include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection erros!!!</br>";}
include_once("inc/class_sql.inc.php"); 
//------------------ Kategorie aus Eingabe speichern ----------------         
$kat_ok=($_POST['postkategorie']=="no_k")?FALSE:TRUE;
$kat_ok ? $kat = $_POST['postkategorie']: print_r("Kategorie waehlen</br>");

$dis_ok=($_POST['checked_disziplins'][0]=="no_d")?FALSE:TRUE;
$checked_disziplins = $_POST['checked_disziplins'];

$disziplin_input_type = $_POST['disziplin_input_type'];

if ($kat_ok) {
	$sql_disziplin = new sql_disziplin($kat);
	$result=$conn->query($sql_disziplin->get_sql());
	$array_result = $result->fetch_all(MYSQLI_ASSOC);

	foreach ($array_result as $key => $disziplin) 
	{
		if ($dis_ok) {
			$checked = (in_array($disziplin['ID'], $checked_disziplins))? " checked='yes'" : "";
		} else {
			$checked = "";
		}

	    echo "<input type='".$disziplin_input_type."' name='disziplin' id='".$disziplin['Disziplin']."' value='".$disziplin['ID']."' onclick='add_input_field()'".$checked ."/>";
	    echo " ".$disziplin['Disziplin'];
	    echo "</br>";
	}
}

?>
