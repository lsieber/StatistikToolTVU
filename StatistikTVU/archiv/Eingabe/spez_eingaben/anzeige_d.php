<?php

	include('connect.inc.php');


    $disziplin = implode([$_POST['postdisziplin'],"%"]);
    $lauf = implode([$_POST['postlauf'],"%"]);

    $rmitglied=mysql_query(      //r steht für result
            "SELECT * FROM `disziplin` WHERE Disziplin LIKE '$disziplin'
            ");
    if (! $rmitglied){         // Check ob Abfrage gelungen ist
    echo"query mistake" . mysql_error();
    }

    $counter=0;
    $mdisziplin=array();   //m steht für matrix

    while($row=mysql_fetch_assoc($rmitglied)) {

        $mdisziplin[$counter][0]= $row['ID'];
        $mdisziplin[$counter][1]= $row['Disziplin'];
        $mdisziplin[$counter][2]= $row['Lauf'];
        $mdisziplin[$counter][3]= $row['Laufsort'];     // Platz für Kategorie
        $counter++;
    }

    echo "Folgende Disziplinen mit ähnlichen Namen sind bereits in der Datenbank vorhanden:";
    echo "</br>";
    if ($counter==0) {
        echo "</br>";
    }else{
        for ($i=0; $i <$counter ; $i++) { 
            echo "<b>";
        	for ($j=1; $j <4 ; $j++) { 

        		echo ($mdisziplin[$i][$j]);
        		echo " ";
        	}
            echo "</b>";
        	echo "</br>";

        }
    }
    
    	
?>
