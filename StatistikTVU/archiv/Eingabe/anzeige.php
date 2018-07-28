<?php
//----------- Includes ---------------------------------
include ('config.php');


//----------- Verbindung mit dem Server herstellen -------------
include ('connect.inc.php');
//**********************************************
//----------- Daten Aufbereiten ----------------
//**********************************************
// 
//----------- Daten Mitglieder -----------------
    $rmitglied=mysql_query(      //r steht für result
            "SELECT * FROM mitglied
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
        $mmitglied[$counter][6]= $row['aktiv'];
        $counter++;
    }
    $mmitgliedsize=$counter; // Anzahl der Mitglieder=Grösse Matrix
    /*
    // Einteilen in Kategorien
    
    for ($i=0;$i<$mmitgliedsize;$i++){
        if ($mmitglied[$i][3]>$Jahr-10){
            $mmitglied[$i][5]="10";    
        }
        elseif ($mmitglied[$i][3]>$Jahr-12){
            $mmitglied[$i][5]="12"; 
        }
        elseif ($mmitglied[$i][3]>$Jahr-14){
            $mmitglied[$i][5]="14";
        }
        elseif ($mmitglied[$i][3]>$Jahr-16){
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
    */

//----- Liste der Wettkämpfe ------
    $rwettkampf=mysql_query(      //r steht für result
            "SELECT * FROM wettkampf
            WHERE sichtbar=1
            ORDER BY Datum
            ");
    if (! $rwettkampf){         // Check ob Abfrage gelungen ist
    echo"query mistake" . mysql_error();
    }

    //Eintragen der Wettkämpfe in Matrix $mwettkampf
    $counter=0;
    $mwettkampf=array();   //m steht für matrix

    while($row=mysql_fetch_assoc($rwettkampf)) {

        $mwettkampf[$counter][0]= $row['ID'];
        $mwettkampf[$counter][1]= $row['WKname'];
        $mwettkampf[$counter][2]= $row['Ort'];
        $mwettkampf[$counter][3]= $row['Datum'];
        $mwettkampf[$counter][4]= $row['Jahr'];
        $counter++;
    }
    $mwettkampfsize=$counter; // Anzahl der Wettkämpfe=Grösse Matrix


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
        $mdisziplin[$counter][7]= $row['U18'];
        $mdisziplin[$counter][8]= $row['U20'];
        $mdisziplin[$counter][9]= $row['Frauen'];
        $mdisziplin[$counter][10]= $row['Manner'];
        $mdisziplin[$counter][11]= $row['MaxVal'];
        $mdisziplin[$counter][12]= $row['MinVal'];

        $counter++;
    }
    $mdisziplinsize=$counter; // Anzahl der Disziplinen=Grösse Matrix

 ?>