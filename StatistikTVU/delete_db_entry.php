<?php

include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection erros!!!</br>";}
include_once("inc/functions_display.php"); 


$sql="DELETE FROM `bestenliste` WHERE `bestenliste`.`ID` = ".$_POST['postid'].";" ;
$result=$conn->query($sql);
($result==1)?print_r('ID: '.$_POST['postid'].' erfolgreich gelöscht!</br></br>'):print_r("Löschen fehlgeschlagen!!!</br></br>");

last_3_entries($conn);

?>
