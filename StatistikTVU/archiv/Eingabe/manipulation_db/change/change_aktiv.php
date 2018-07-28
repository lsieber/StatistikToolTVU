<?php 

include ('../../../inc/connect.inc.php');

$id = $_POST['postid'];
$aktiv = $_POST['postaktiv'];

if (empty($id)){
echo "id eingeben";    
//}else if (empty($aktiv)){
//echo "aktiv eingeben";
}else {
   	$sql="UPDATE mitglied SET aktiv='$aktiv' WHERE ID='$id' " ;
    $query = mysql_query ($sql);
    echo("Eingabe hat geklapt, neuer Wert: ".$aktiv);
}       


 ?>