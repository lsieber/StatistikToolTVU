<?php
include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection erros!!!</br>";}
include_once("inc/class_sql.inc.php"); 
$sql = new sql_generator();

$kat_ok=($_POST['postkategorie']=="no_k")?FALSE:TRUE;
$kat_ok ? $kat = $_POST['postkategorie']: print_r("Kategorie waehlen</br>");

$wettkampf_ok = ($_POST['postwettkampf']=="no_w")?FALSE:TRUE;
$wettkampf_ok ? $wettkampf = $_POST['postwettkampf']: print_r("Wettkampf waehlen</br>");

if ($wettkampf_ok && $kat_ok) {
	$sql_wk = new sql_get_distinct_dataset('wettkampf',$wettkampf, 'Ort, Datum, Jahr, WKname');
	$wk_result = $conn->query($sql_wk->get_sql());
	$wk_array = $wk_result->fetch_all(MYSQLI_ASSOC);
	$jahr = $wk_array[0]["Jahr"];

	$sex = "all";
	if ($kat=="men" OR $kat =="wom") {
	    $sex = ($kat == "wom")? 1:2;
	    $kat = "aktiv";
	}
	gettype($kat)=="array" ? :$kat= array($kat);

	$sql_mitglied = new sql_mitglied($year=$jahr,$kategorie=$kat,$sex=$sex);
	$result=$conn->query($sql_mitglied->get_sql());
	$array_result = $result->fetch_all(MYSQLI_ASSOC);
	
	$result_team=$conn->query($sql_mitglied->get_sql_for_teams());
	$array_result_team = $result_team->fetch_all(MYSQLI_ASSOC);
	foreach (array_merge($array_result,$array_result_team) as $key => $person) 
	{
	    echo "<input type='radio' name='mitglied' value='".$person['ID']."'/>";
	    echo " ".$person['Vorname']." ".$person['Name'];
	    echo "</br>";
	}
}

?>
