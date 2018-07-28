<?php

include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection ERROR</br>";}



$kat_ages = [ "U10" => ["minage"=>5, "maxage" => 9],
				"U12" => ["minage"=>10, "maxage" => 11],
				"U14" => ["minage"=>12, "maxage" => 13],
				"U16" => ["minage"=>14, "maxage" => 15],
				"aktiv" => ["minage"=>16, "maxage" => 100]
				];
$myHeaders = array("Number of Leistungen", "Wettkampfname", "Ort", "Datum", "ID");

foreach ($kat_ages as $kat => $ages) {
	$minJG = $_POST["years"]-$ages["maxage"];
	$maxJg = $_POST["years"]-$ages["minage"];
	$list_of_competitions = list_one_year($_POST["years"], $conn, $minJG, $maxJg);

	$csvName = 'Wettkämpfe/'.$_POST["years"].'/wettkämpfe'.$_POST["years"].'_'.$kat.'.csv';
	$fp = fopen($csvName, 'w');
	fputcsv($fp, $myHeaders, ";");
	foreach ($list_of_competitions as $line) {
	    fputcsv($fp, array_map("utf8_decode", $line), ";");
	}
	fclose($fp);
}
// Print for all Kategories
$list_of_competitions = list_one_year($_POST["years"], $conn, 1911, $_POST["years"]+1);
$csvName = 'Wettkämpfe/'.$_POST["years"].'/wettkämpfe'.$_POST["years"].'_allTVU.csv';
$fp = fopen($csvName, 'w');
fputcsv($fp, $myHeaders, ";");
foreach ($list_of_competitions as $line) {
    fputcsv($fp, array_map("utf8_decode", $line), ";");
}
fclose($fp);

echo "Die Liste ist in folgendem File gespeichert: ". $csvName;

function list_one_year ($year, $conn, $minJG, $maxJg){
    $sql = "SELECT COUNT(*) as number_leistungen, wettkampf.WKname, wettkampf.Ort, wettkampf.Datum , wettkampf.ID FROM bestenliste INNER JOIN wettkampf ON wettkampf.ID=bestenliste.Wettkampf INNER JOIN mitglied ON mitglied.ID=bestenliste.Mitglied WHERE wettkampf.Jahr = ".$year." AND mitglied.Jg <=".$maxJg." AND mitglied.Jg>=".$minJG." GROUP BY bestenliste.Wettkampf ORDER BY Datum ASC";

    $result=$conn->query($sql);
    $array_result = $result->fetch_all(MYSQLI_ASSOC);
    return $array_result;
}


?>