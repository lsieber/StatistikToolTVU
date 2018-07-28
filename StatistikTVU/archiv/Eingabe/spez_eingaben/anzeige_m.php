<?php

	include('connect.inc.php');


    $vorname = implode([$_POST['postvorname'],"%"]);
    $name = implode([$_POST['postname'],"%"]);
    $jg = $_POST['postjg'];


    $rmitglied=mysql_query(      //r steht für result
            "SELECT * FROM `mitglied` WHERE Name LIKE '$name' AND Vorname LIKE '$vorname'
            ");
    if (! $rmitglied){         // Check ob Abfrage gelungen ist
    echo"query mistake" . mysql_error();
    }

    //Eintragen der Mitglieder in Matrix $mmitglied
    $counter=0;
    $mmitglied=array();   //m steht für matrix

    while($row=mysql_fetch_assoc($rmitglied)) {

        $mmitglied[$counter][0]= $row['ID'];
        $mmitglied[$counter][1]= $row['Vorname'];
        $mmitglied[$counter][2]= $row['Name'];
        $mmitglied[$counter][3]= $row['Jg'];
        $mmitglied[$counter][4]= $row['Geschlecht'];
        $mmitglied[$counter][5]= $row['aktiv'];;        // Platz für Kategorie
        $counter++;
    }
    $mmitgliedsize=$counter; // Anzahl der Mitglieder=Grösse Matrix


    echo "Folgende Athleten mit ähnlichen Namen sind bereits in der Bestenliste vorhanden:";
    echo "</br>";
    if ($counter==0) {
        echo "</br>";
    }else{
        for ($i=0; $i <$counter ; $i++) { 
            echo "<b>";
        	for ($j=1; $j <4 ; $j++) { 

        		echo ($mmitglied[$i][$j]);
        		echo " ";
        	}
            echo "</b>";
        	echo "</br>";

        }
    }
    
    	
?>
