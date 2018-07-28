<?php 

include ('connect.inc.php');

$disziplin=$_POST['disziplin'];
$lauf=$_POST['lauf'];
$min_value = $POST['min_value'];
$max_value = $POST['max_value'];

if($lauf==1) {          // Läufe sollen nach der Länge des Disziplinennamens Sortiert werden, damit nicht zb. 100m vor 60m kommt
    $Laufsort=strlen($disziplin);
}
else{               // sonst ist die Reihenfloge nicht so entscheidend
    $Laufsort=0;
}

$sichthelp=$_POST['sich'];
$sichthelp[sizeof($sichthelp)] = 'null';
$id=0;
for ($i=0; $i < 8; $i++) { // 0:10 1:12 2:14 3:16 4:18 5:20 6:201 7:202
    if ($sichthelp[$id] == 2*$i+10 or $sichthelp[$id] == 195+$i) {
        $sicht[$i] = 1;
        $id++;
    }else{
        $sicht[$i] = 0;
    }
}
    

if (empty($disziplin)){
echo "disziplin eingeben";    
} else {
   $sql="INSERT INTO disziplin VALUES ('Null','$disziplin','$lauf',
        '$Laufsort','$sicht[0]','$sicht[1]','$sicht[2]','$sicht[3]'
        ,'$sicht[4]','$sicht[5]','$sicht[6]','$sicht[7]','$max_value','$min_value')" ;
   $query = mysql_query ($sql);
    
   header('Location:Eingabe_a_w_d.html');    
}

 ?>