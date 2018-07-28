<?php 

include ('connect.inc.php');
$ort = " ";
for ($i=1950; $i < 2080 ; $i++) { 
	$datum = implode([$i,"-01-01"]);
    $sql="INSERT INTO wettkampf VALUES ('$i','$ort','$datum','$i','0','$i')" ;
    $query = mysql_query ($sql);
    echo ($i);
  }
  echo "mission complete";
 ?>