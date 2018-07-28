<?php
include_once("inc/connect.inc.php"); 
if (!$conn->connect_error) {echo "Connected successfully</br>";}
$kat_dic =array("1"=>"U10", "2"=>"U12", "3"=>"U14","4"=>"U16","5"=> "U18", "6"=>"U20", "7"=>"Aktiv");
$sex_dict =array("3" => " W", "4"=> " M", "5" => "Mixed");
for ($i=15100; $i < 15800; $i++) { 
	$str = str_split($i);
	$jahr = 1950 + intval($str[3])*10 + intval($str[4]);
	$kat_key = $str[2];
	$sex = intval($str[1]);
	if ($kat_key !=7) {
		$kat = $kat_dic[$kat_key]." ".$sex_dict[$sex];
	}else{
		if ($sex == 3) {
			$kat = "Frauen";
		}elseif ($sex == 4) {
			$kat = "Männer";
		}elseif($sex == 5){
			$kat = "Mixed Aktiv";
		}else{
			echo "</br>ERROOOOOORR</br>";
		}
	}
	$sql = "INSERT INTO `mitglied`(`ID`, `Name`, `Vorname`, `Jg`, `Geschlecht`, `aktiv`) VALUES (".$i.",'".$kat."','TVU',".$jahr.",".$sex.",-100)";
	echo $sql;
	$result=$conn->query($sql);
	if ($result!=1) {
		echo "insert not succesfull";
	}
	echo "ID: ".$i;
echo ", Jahr: ".$jahr;
echo ", kategorie: ".$kat;
echo ", Geschlecht: ".$sex. '</br>';

}
?>