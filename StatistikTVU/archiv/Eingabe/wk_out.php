<?php

include "anzeige.php";

//------------------ Kategorie aus Eingabe speichern ----------------
  
$wettkampf = $_POST['postwettkampf'];

function second2normal($time_s){
    //settype($time_s, "double");
    $stunden = $time_s/3600;
    settype($stunden, "integer");
    $minuten = $time_s/60-($stunden*60);
    settype($minuten, "integer");
    $sekunden = $time_s - $stunden*3600 - $minuten*60;
    settype($sekunden, "integer");
    $hundertstel = round (100*($time_s - $stunden*6000 - $minuten*60 - $sekunden) , 0);
    settype($hundertstel, "integer");

    if($hundertstel==0)  // Falls keine hunderstel Voranden,
    {                     // füge hinten zwei nullen an
        $hundertstel = "00";
    }
    if($hundertstel==1 or $hundertstel==2 or $hundertstel==3 or
       $hundertstel==4 or $hundertstel==5 or $hundertstel==6 or
       $hundertstel==7 or $hundertstel==8 or $hundertstel==9)
    {    
        $hundertstel = implode(["0",$hundertstel]);
    }

    if ($time_s > 60) 
    { 
        if($sekunden==0)  
        {   
            $sekunden = "00";
        }
        if($sekunden==1 or $sekunden==2 or $sekunden==3 or
           $sekunden==4 or $sekunden==5 or $sekunden==6 or
           $sekunden==7 or $sekunden==8 or $sekunden==9)
        {   
            $sekunden = implode(["0",$sekunden]);
        }  
        if ($time_s > 3600) 
        {
            if($minuten==0)  {   $sekunden = "00";}
            if($minuten==1 or $minuten==2 or $minuten==3 or
               $minuten==4 or $minuten==5 or $minuten==6 or
               $minuten==7 or $minuten==8 or $minuten==9)
            {       
                $minuten = implode(["0",$minuten]);
            } 
            return implode([$stunden,":",$minuten,":",$sekunden,".",$hundertstel]);
        }else{      // 60s < $time_s < 3600s
            return implode([$minuten,":",$sekunden,".",$hundertstel]);
        }
    }else{          // $time_s  < 60s
        return implode([$sekunden,".",$hundertstel]);
    }
}

//----------- Verbindung mit dem Server herstellen -------------
include ('connect.inc.php');
//**********************************************
//----------- Daten Aufbereiten ----------------
//**********************************************
// 
//----- Finden des Jahrs des ausgewählten Wettkampfes ------
   $rwettkampf=mysql_query(      //r steht für result
            "SELECT * FROM wettkampf
            WHERE ID=$wettkampf
            ORDER BY Datum
            ");
    if (! $rwettkampf){         // Check ob Abfrage gelungen ist
    echo"query mistake" . mysql_error();
    }

    //Eintragen der Wettkämpfe in Matrix $mwettkampf
    $mwettkampf=array();   //m steht für matrix
    while($row=mysql_fetch_assoc($rwettkampf)) {
        $wk_date = $row['Datum'];
        $wk_name = $row['WKname'];
        $wk_place = $row['Ort'];
        $wk_year = $row['Jahr'];
    }

echo($wk_name);
echo', ';
echo($wk_date);
echo', ';
echo($wk_place);


   $rleistungen=mysql_query(      //r steht für result
            "SELECT 
            b.Leistung, m.Vorname, m.Name, m.Jg,
            d.Disziplin,b.ID,d.Lauf
            FROM bestenliste b
            INNER JOIN Disziplin d ON(b.DisziplinID=d.ID)
            INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
            WHERE b.Wettkampf = $wettkampf
            ORDER BY b.ID
        ");
    if (! $rleistungen){         // Check ob Abfrage gelungen ist
    echo"query mistake" . mysql_error();
    }

    //Eintragen der Wettkämpfe in Matrix $mwettkampf
    $counter = 0;
    $leistung=array();   //m steht für matrix
    while($row=mysql_fetch_assoc($rleistungen)) {
        $leistung[$counter] = $row;
        $counter++;
    }
    $leistung_size = $counter;

 ?>

<!doctype html>
<table cellspacing="10">
    <?php  for ($i=0;$i<$leistung_size;$i++){
    echo"<tr>";
        echo"<td>";
        if($leistung[$i]['Lauf']==1){
            echo(second2normal($leistung[$i]['Leistung']));
        }else{
            echo($leistung[$i]['Leistung']);
        }
        echo"</td>";

        echo"<td>";
            echo($leistung[$i]['Disziplin']);
        echo"</td>";

        echo"<td>";
            echo($leistung[$i]['Vorname']);
        echo"</td>";

        echo"<td>";
            echo($leistung[$i]['Name']);
        echo"</td>";  

        echo"<td>";
            echo($leistung[$i]['Jg']);
        echo"</td>";  

        echo"<td>";
            echo($leistung[$i]['ID']);
        echo"</td>";       
    echo"</tr>";
    if($i%5 == 4){
        echo"<tr><tr>";
    }
    }



?>





</table>

