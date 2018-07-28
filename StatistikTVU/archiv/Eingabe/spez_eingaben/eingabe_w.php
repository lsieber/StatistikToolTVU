<?php 

include ('connect.inc.php');

$name=$_POST['w_name'];
$ort=$_POST['ort'];
$datum=$_POST['datum'];

$teile = explode("-", $datum);
$Jahr= $teile[0];


if (empty($name)){
echo "name eingeben";    
}else if (empty($ort)){
echo "ort eingeben";
} else if (empty($datum)){
echo "datum eingeben";
} else {
   $sql="INSERT INTO wettkampf VALUES ('Null','$ort','$datum','$name','1','$Jahr')" ;
   $query = mysql_query ($sql);
  
   header('Location:Eingabe_a_w_d.html');    
}

 ?>