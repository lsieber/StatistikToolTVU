<?php 
//*******************************************************************
//-------Eingabefile zum manipulieren der Daten in Bestenliste------
//*******************************************************************

//----------------- includes --------------------------------------
     include('anzeige.php');

// ----------------- Erfassen der Daten aus der IndexDatei ---------

// --------------------Disziplin ---------------------------------
    $Disziplin=$_POST['postdisziplin'];
    $bd=0;                 // boolean ob disziplin gewählt wurde
    $DisziplinID=0;                             // wird unten gebraucht zum check des min max value
    if ($Disziplin=="no_d") 
    {
        echo "bitte Disziplin w&auml;hlen";    // Anweisung falls keine Disziplin eigegeben
        $bd=0;                                 // Keine Disziplin gewählt
    } else
    {
        for($i=0;$i<$mdisziplinsize;$i++) {     // Gib Gewählte Disziplin aus
            if ($mdisziplin[$i][0]==$Disziplin){
                echo "Disziplin: ";
                echo $mdisziplin[$i][1];
                $DisziplinID = $i;              // wird unten gebraucht zum check des min max value
                $bd=1;                          // Disziplin gewählt = wahr
            }     
        }
    } 
    ?> <br /> <?php

//----------------------------Wettkampf--------------------------    
    
    $Wettkampf=$_POST['postwettkampf'];
    $bw=0;                 // boolean ob wettkampf gewählt wurde
    if ($Wettkampf=="no_w")
    {
        echo "bitte Wettkampf w&auml;hlen";    // Anweisung falls kein Wettkanpf eigegeben
        $bw=0;                                 // Kein Wettkampf gewählt
    }else
    {
        for($i=0;$i<$mwettkampfsize;$i++) {       // Gibt gewählten Wettkampf aus
            if ($mwettkampf[$i][0]==$Wettkampf){
                echo "Wettkampf: ";
                echo $mwettkampf[$i][1];
            $bw=1;                                 // Wettkampf gewählt = wahr
            }
        }    
    }
    ?> <br /> <?php
//----------------------------Mitlgied----------------------------    
    $Mitglied=$_POST['postmitglied'];
    $bm=0;                 // boolean ob mitglied gewählt wurde
    if ($Mitglied=="no_m")
    {
        echo "bitte Athlet w&auml;hlen";    // Anweisung falls kein Mitglied eigegeben
        $bm=0;                                 // Kein Mitglied gewählt
    }else if($Mitglied=="no_k")
    {
        echo "bitte Kategorie w&auml;hlen";
        $bm=0;
    }else
    {
        for($i=0;$i<$mmitgliedsize;$i++) {       // Gibt gewähltes Mitglied aus
            if ($mmitglied[$i][0]==$Mitglied){
                echo "Athlet: ";
                echo $mmitglied[$i][2];
                echo " ";
                echo $mmitglied[$i][1];
            $bm=1;                                 // Mitglied gewählt = wahr
            }
        }
    }
    ?> <br /> <?php 
//---------------------Leistung-----------------------------     
    //check ob es sich um Laufdisziplin handet (wegen zeiten anzeige)
    // Abfragen der Laufeigenschaft der Disziplin die gewählt wurde
    for($i=0;$i<$mdisziplinsize;$i++)
    {
        if($Disziplin==$mdisziplin[$i][0])
        {
            $Lauf=$mdisziplin[$i][2];
        }
    }
    
    $bl=0;
    
    if($Lauf==1 or $Lauf==5 )     //Für Läufe und Staffel
    {
        $Leistunghelp=$_POST['postleistung'];
        if($Leistunghelp=="no_l")
        {
            echo "bitte Leistung eingeben";
            $bl=0;
        }
        else
        {
            $teile = explode(":", $Leistunghelp);
            $teilesize= sizeof($teile);
            
            if($teilesize==1) //keine Minuten
            {
                $Leistung=$Leistunghelp;
                echo "Leistung: ";
                echo $Leistung;
                ?> <br /> <?php
                $bl=1;
            }
            else if($teilesize==2)
            {
                $Leistung=$teile[0]*60+$teile[1];
                echo "Leistung: ";
                echo $Leistung;
                ?> <br /> <?php
                $bl=1;   
            }
            else if($teilesize==3)
            {
                $Leistung=$teile[0]*3600+$teile[1]*60+$teile[2];
                echo "Leistung: ";
                echo $Leistung;
                ?> <br /> <?php
                $bl=1;
            }     
            else
            {
                echo "syntaxfehler!!!!" ;
                $bl=0;
            }
          
        }
    }
    else
    {
        $Leistung=$_POST['postleistung'];
        $bl=0;
        if($Leistung=="no_l")
        {
            echo "bitte Leistung eingeben <br />";
            $bl=0;
        }
        else
        {
            echo "Leistung: ".$Leistung." <br /> ";
            $bl=1;
        }  
    }
    

 // Check ob Wert zwischen min und max value liegt   
$bminmax = 1;   
if ($mdisziplin[$DisziplinID][11]<$Leistung or $mdisziplin[$DisziplinID][12]>$Leistung ) {
    $bminmax = 0;
    echo "Die Leistung entspricht nicht den Minimal oder maximal Vorgaben <br />";
}   
    
    
//**************************************************************
//                  EINGABE IN DANTENBANK
//**************************************************************

// ----------- Liste der bestehenden Leistungen -----------------

$e_ok=$bl*$bm*$bw*$bd*$bminmax;


if ($e_ok==1)   //nur falls alles ausgewählt und eingegeben!!!!!
{
    
    $sql="INSERT INTO bestenliste VALUES ('Null','$Mitglied','$Wettkampf','$Disziplin','$Leistung')" ;
    $query = mysql_query ($sql);
    echo "Letzte Eingabe erfolgreich";
}   
else
{
    echo "Eingabe nicht gegl&uuml;ckt";
}
    
    

 ?>