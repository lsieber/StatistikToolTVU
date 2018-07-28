<?php 
    //Eingabefile zum manipulieren der Daten in Bestenliste
    
    include('connect.inc.php');
    
//----------------- Leistung -----------------------  
    $Leistung = $_POST['Leistung'];
    
//----------------- Disziplin ----------------------
    if(isset($_POST["disziplin"])) {
        $disziplin=$_POST['disziplin'];

        echo("wettkampf choosen ".$disziplin);
    }
    else{
       echo("no wettkampf selected");
    }
 //---------------- Wettkampf --------------------   
    if(isset($_POST['wettkampf'])) {
        $wettkampf=$_POST['wettkampf'];

        echo("kat choosen ".$wettkampf);
    }
    else{
       echo("no kategorie selected");
    }
//------------------ Mitglied ----------------------
    if(isset($_POST['mitglied'])) {
        $mitglied=$_POST['mitglied'];

        echo("mitgl choosen ".$mitglied);
    }
    else{
       echo("no mitgl selected");
    }

    //Falls ungültige Eingabe, zurück zur Index Seite
    if (empty($Leistung)) {
       header('Location:eingabeindex.php');
    }
    //Falls gültige Eingabe
    else{                         //unten auch mehrere möglich (mit , trennen)
        $sql="INSERT INTO bestenliste VALUES ('Null','$mitglied','$wettkampf','$disziplin','$Leistung')" ;
        $query = mysql_query ($sql);
        echo "Erfolgreich";   
    }
    
    
            


 ?>