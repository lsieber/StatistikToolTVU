<?php

include "anzeige.php";

//------------------ Kategorie aus Eingabe speichern ----------------
  
$kat=$_POST['postkategorie'];
$wettkampf = $_POST['postwettkampf'];

//----------- Verbindung mit dem Server herstellen -------------
include ('connect.inc.php');
//**********************************************
//----------- Daten Aufbereiten ----------------
//**********************************************
// 
//----- Finden des Jahrs des ausgewählten Wettkampfes ------
   $rwettkampf=mysql_query(      //r steht für result
            "SELECT * FROM wettkampf
            WHERE sichtbar=1 AND id=$wettkampf
            ORDER BY Datum
            ");
    if (! $rwettkampf){         // Check ob Abfrage gelungen ist
    echo"query mistake" . mysql_error();
    }

    //Eintragen der Wettkämpfe in Matrix $mwettkampf
    $mwettkampf=array();   //m steht für matrix
    while($row=mysql_fetch_assoc($rwettkampf)) {
        $date = $row['Datum'];
    }
    $ymd = explode("-", $date);
    $year = $ymd[0];

//----------- Daten Mitglieder -----------------
    $rmitglied=mysql_query(      //r steht für result
            "SELECT * FROM mitglied
            WHERE Jg+aktiv+16>$year
            ORDER BY Geschlecht, Vorname
            ");
    if (! $rmitglied){         // Check ob Abfrage gelungen ist
    echo"query mistake" . mysql_error();
    }

    //Eintragen der Mitglieder in Matrix $mmitglied
    $counter=0;
    $mmitglied=array();   //m steht für matrix

    while($row=mysql_fetch_assoc($rmitglied)) {

        $mmitglied[$counter][0]= $row['ID'];
        $mmitglied[$counter][1]= $row['Name'];
        $mmitglied[$counter][2]= $row['Vorname'];
        $mmitglied[$counter][3]= $row['Jg'];
        $mmitglied[$counter][4]= $row['Geschlecht'];
        $mmitglied[$counter][5]= "null";        // Platz für Kategorie
        $counter++;
    }
    $mmitgliedsize=$counter; // Anzahl der Mitglieder=Grösse Matrix
    
    // Einteilen in Kategorien
    
    for ($i=0;$i<$mmitgliedsize;$i++){
        if ($mmitglied[$i][3]>$year-10 and $mmitglied[$i][3]<$Jahr-5){
            $mmitglied[$i][5]="10";    
        }
        elseif ($mmitglied[$i][3]>$year-12){
            $mmitglied[$i][5]="12"; 
        }
        elseif ($mmitglied[$i][3]>$year-14){
            $mmitglied[$i][5]="14";
        }
        elseif ($mmitglied[$i][3]>$year-16){
            $mmitglied[$i][5]="16";
        }
        else{
            if($mmitglied[$i][4]==1){
                $mmitglied[$i][5]="201";
            } elseif ($mmitglied[$i][4]==2)
            {
                $mmitglied[$i][5]="202";
            } else {
                $mmitglied[$i][5]="203";
            }
  
          
        }
    } 
    



/* 
//----Liste der Disziplinen------
   $rdisziplin=mysql_query(
            "SELECT * FROM disziplin ORDER BY Lauf, Laufsort, Disziplin ");
    if (! $rdisziplin){
    echo"query mistake" . mysql_error();
    }

    //Eintragen der Disziplinen in Matrix $mdisziplin
    $counter=0;
    $mdisziplin=array();   //m steht für matrix

    while($row=mysql_fetch_assoc($rdisziplin)) {

        $mdisziplin[$counter][0]= $row['ID'];
        $mdisziplin[$counter][1]= $row['Disziplin'];
        $mdisziplin[$counter][2]= $row['Lauf'];
        $mdisziplin[$counter][3]= $row['U10'];
        $mdisziplin[$counter][4]= $row['U12'];
        $mdisziplin[$counter][5]= $row['U14'];
        $mdisziplin[$counter][6]= $row['U16'];
        $mdisziplin[$counter][7]= $row['Frauen'];
        $mdisziplin[$counter][8]= $row['Manner'];

        $counter++;
    }
    $mdisziplinsize=$counter; // Anzahl der Disziplinen=Grösse Matrix
*/
 ?>

    
<!doctype html>
    <?php  for ($i=0;$i<$mmitgliedsize;$i++){
    
        if($mmitglied[$i][5]==$kat){
            ?>  <input
            type="radio"
            name="mitglied"
            value="<?php echo $mmitglied[$i][0] ?>" />
            <?php echo $mmitglied[$i][2]; ?> &nbsp;
            <?php echo $mmitglied[$i][1]; ?> 
            <br /> <?php
        }
        if($mmitglied[$i][5]==203){    //gemischte Teams Staffeln anzeigen
            if($kat==201 or $kat==202){           // bei Männern und Frauen anzeigen
               ?>  <input
                type="radio"
                name="mitglied"
                value="<?php echo $mmitglied[$i][0] ?>" />
                <?php echo $mmitglied[$i][2]; ?> &nbsp;
                <?php echo $mmitglied[$i][1]; ?>
                <br /> <?php     
            }
        }
        
    }
?>
