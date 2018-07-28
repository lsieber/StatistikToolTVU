<?php
include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection erros!!!</br>";}
include_once("inc/class_sql.inc.php"); 
require_once("inc/TimeUtils.php"); 


$sql = new sql_generator();

$sql_mitglied = new sql_get_distinct_dataset("mitglied",$_POST["mitglied_id"],"*");
$m_result = $conn->query($sql_mitglied->get_sql());
$result_mitglied = $m_result->fetch_all(MYSQLI_ASSOC);

foreach ($_POST["disziplin_id"] as $key => $value) {
	echo $value;
	if ($key!=sizeof($_POST["disziplin_id"])-1) {
 		echo",";
 	}
	$sql_disziplin = new sql_get_distinct_dataset("disziplin",$value,"*");
	$d_result = $conn->query($sql_disziplin->get_sql());
	$result_disziplin = $d_result->fetch_all(MYSQLI_ASSOC);
	$disziplin_Lauf[$key] = $result_disziplin[0]["Lauf"];


	if ($result_mitglied[0]["Geschlecht"] == 1 || $result_mitglied[0]["Geschlecht"] == 3) 
	{
		$punkteslv2010_id = $result_disziplin[0]["PunkteSLV2010IDFrau"];
	} 
	else 
	{
		$punkteslv2010_id = $result_disziplin[0]["PunkteSLV2010IDMan"];
	}
	if ($_POST["performance"][$key] == "" || $punkteslv2010_id == 0 || $punkteslv2010_id == NULL) {
		$points_array[$key] = "no points";
	}else{
		$sql_parametertable = new sql_get_distinct_dataset("punkteslv2010",$punkteslv2010_id,"*");
		$p_result = $conn->query($sql_parametertable->get_sql());
		$result_parameter = $p_result->fetch_all(MYSQLI_ASSOC);

		$points = PointCalculator($result_parameter[0]["parameter_a"],$result_parameter[0]["parameter_b"],$result_parameter[0]["parameter_c"],$result_parameter[0]["LaufFormel"],time2seconds($_POST["performance"][$key]));
		$points_array[$key] = $points;
	}

}
echo "//";
foreach ($disziplin_Lauf as $key => $value) {
	echo $value;
	if ($key != sizeof($disziplin_Lauf)-1) {
		echo ",";
	}
}
echo "//";

foreach ($points_array as $key => $value) {
	echo $value;
	if ($key != sizeof($points_array)-1) {
		echo ",";
	}
}



function PointCalculator($A, $B, $C, $Lauf, $performance)
{
	/* 
	Auszug aus dem wertungstabellenblatt:
	Lauf=GANZZAHL(A*((B-Leistung in Hundertstel)/100)^C)
	Wurf- und Sprung=GANZZAHL(A*((Leistung in Centimeter-B)/100)^C)
	*/
	if ($Lauf == 0) 
	{
		$points = floor($A*(($performance*100-$B)/100)**$C);

	}
	elseif ($Lauf == 1)
	{
		$points = floor($A*(($B-$performance*100)/100)**$C);
	}
	else
	{
		$points = "ERROR";
	}

	$points = ($points=="NAN")?0:$points;
	return $points;

}



?>
