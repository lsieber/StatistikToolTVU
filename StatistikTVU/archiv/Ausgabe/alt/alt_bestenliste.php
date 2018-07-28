 <!Doctype html>

<?php

// Eigenschaften der Bestenliste
// Für U10, U12, U14, U16 nur zahlenwert eingeben (ohne U), für Aktive Wert 20
$Kategorieo=10; // Fürs Direkte manipulieren im File
$Kategorieo=$_POST['kategorie'];  //Wert aus file index //obere grenze
if($Kategorieo==200){
   $Kategorieu=15; 
}else{
    $Kategorieu=$Kategorieo-3;
}  
if($Kategorieo==10){
    $Kategorieu=0;
}  

// Weiblich wert 1, männlich wert 2
$Geschlecht=2;     // Fürs Direkte manipulieren im File
$Geschlecht=$_POST['sex'];      //Wert aus file index

//Jahr eingeben für welches die Bestenliste erstellt werden soll
$Jahr=2013;       // Fürs Direkte manipulieren im File
$Jahr=$_POST['year'];            //Wert aus file index


// ------------------ Verbindunge erstellen ------------------------ 
    $conn= mysql_connect("localhost", "root", "");
    if (!$conn) {
    echo "Unable to connect to DB: " . mysql_error();
    exit;
    }
    
    if (!mysql_select_db("bestenliste")) {
    echo "Unable to select bestenliste: " . mysql_error();
    exit;
    }
    
// *******************************************************************    
//------Erster Teil der Matrix erstellen die ausgegebne Wird -------
//-----------------------LAEUFE!!!!!--------------------------------
// ******************************************************************
    
// ---------------- Doppelte Leistungen ausscheiden ---------------- 
    
//------Liste der Leistungen Mit Läufen  ----
    //Infos: Lauf für Infos über Reihenfolge Lauf, Sprünge, Würfe, Mehrkampf
    //Leistung; Disziplinname; Mitglied; 
    $rraw=mysql_query("SELECT 
            d.Lauf, b.Leistung, b.ID, d.Disziplin, b.Mitglied,
            m.Geschlecht, m.Jg, d.Laufsort, b.DisziplinID, w.Jahr
            FROM bestenliste b
            INNER JOIN Disziplin d ON(b.DisziplinID=d.ID)
            INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
            INNER JOIN Wettkampf w ON(b.Wettkampf=w.ID)
            WHERE Geschlecht=$Geschlecht AND Jg>($Jahr-$Kategorieo) 
            AND Jg<($Jahr-$Kategorieu) AND Jahr=$Jahr AND Lauf=1
            ORDER BY Lauf, Laufsort, Disziplin, Leistung, ID
            ");
    if (! $rraw){
    echo"query mistake" . mysql_error();        
} 

    $counter=0;
    $matrix=array();

    while($row=mysql_fetch_assoc($rraw)) {

        $mleistung[$counter][0]= $row['ID'];   
        $mleistung[$counter][1]= $row['Leistung'];
        $mleistung[$counter][2]= $row['Mitglied'];
        $mleistung[$counter][3]= $row['DisziplinID'];
        $mleistung[$counter][4]= "NULL";           // Zeile zum markieren welche gelöscht werden
        
        $counter++;

        }

        $mleistungsize=$counter; //Variable für die Grösse der Matrix (anzahl Leistungen)   
//---------------------------------TEST-tool------------------------
/*
for ($i=0;$i<$mleistungsize;$i++){
    ?><p><?php echo $mleistung[$i][0];
    echo ", ";
    echo $mleistung[$i][1];
    echo ", ";
    echo $mleistung[$i][2];
    echo ", ";
    echo $mleistung[$i][3];
    echo ", ";
    echo $mleistung[$i][4];

    ?></p><?php
} */    
// --------- Zeilen wo neue Disziplinen beginnen markieren-------
    
    $newdisziplin=array();
    $olddisziplin=NULL;
    $j=0;
    
    for ($i=0;$i<$mleistungsize;$i++){
        if($mleistung[$i][3]!=$olddisziplin){
            $newdisziplin[$j]=$i;
            $j++;    
        }
        $olddisziplin=$mleistung[$i][3];     
    }
    $numberdisziplin=$j;
     
 /*  ANALYSE TOOL   
    echo $mleistungsize; ?> <br /> <?php
    for($i=0;$i<$mleistungsize;$i++){
    
        echo "ID: ";
        echo $mleistung[$i][0];
        echo " , leistung: ";
        echo $mleistung[$i][1];
        echo " , name: ";
        echo $mleistung[$i][2];
        ?> <br /> <?php 
    }  
        */
//------------- Doppelte Leistungen --------------------------------   
    
    for ($j=0;$j<$numberdisziplin;$j++) //Iteration über disziplinen
    {   
        
        $start= $newdisziplin[$j];
        if (($j+1)>=$numberdisziplin)    //overflow beheben
        {                                // ""
            $end=$mleistungsize;         //  ""
        }                                //   ""
        else {                           //    ""
            $end=$newdisziplin[($j+1)];  //     ""
        }
      
        
        for($i=$start;$i<$end;$i++) // Iteration über Leistungen innerhalb der Disziplin
        {  
            for ($k=($i+1);$k<$end;$k++)
            {
                if($mleistung[$i][4]=="NULL" && $mleistung[$k][4]=="NULL" ){
                if($mleistung[$i][3]==$mleistung[$k][3] && 
                    $mleistung[$i][2]==$mleistung[$k][2])
                {
                    
                          
                    if($mleistung[$k][1]<$mleistung[$i][1])
                    {
                        $mleistung[$i][4]=1;    // 1=CANCELN!!!
                               
                    }
                    else
                    {
                        $mleistung[$k][4]=1;     // 1=CANCELN!!!
                                                      
                    } 
                    
                }    
                }
            }            
           
        }
         
    }
    
    
    
    
//****************************************************************
//------------------- Gesamtinformationen einholen ---------------
//****************************************************************           

//********************************************************************    
// -------- Liste mit Detailinformationen zu Leistungen der LAEUFE!!--
//********************************************************************    
     $result=mysql_query("SELECT m.Vorname, m.Name, m.Jg, m.Geschlecht,
            w.Ort, w.Datum, d.Disziplin, d.Lauf, b.Leistung, b.ID, 
            w.WKname, d.Laufsort, w.Jahr
            FROM bestenliste b
            INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
            INNER JOIN Wettkampf w ON(b.Wettkampf=w.ID)
            INNER JOIN Disziplin d ON(b.DisziplinID=d.ID)
            WHERE Geschlecht=$Geschlecht AND Jg>($Jahr-$Kategorieo)
            AND Jg<($Jahr-$Kategorieu) AND Jahr=$Jahr AND Lauf=1 
            ORDER BY Lauf, Laufsort, Disziplin, Leistung, ID
            ");                     //    WHERE Geschlecht=$Geschlecht AND Jg>($Jahr-$Kategorie)
    if (! $result){
   echo"query mistake" . mysql_error();
   
  /*          d.Lauf, b.Leistung, b.ID, b.Disziplin, b.Mitglied,
            m.Geschlecht, m.Jg, d.Laufsort,
            FROM bestenliste b
            INNER JOIN Disziplin d ON(b.Disziplin=d.ID)
            INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
            WHERE Geschlecht=$Geschlecht AND Jg>($Jahr-$Kategorieo)
            AND Jg<($Jahr-$Kategorieu) AND Lauf=1
            ORDER BY Lauf, Laufsort, Disziplin, Leistung, ID
            ");     */
}        

//--------------------- in matrix eintragen ---------------------        
      $counter=0;
      $matrix=array();
      
      while($row=mysql_fetch_assoc($result)) {      
        
        $matrixr[$counter][0]= $row['Disziplin'];   
        $matrixr[$counter][1]= $row['Leistung'];
        $matrixr[$counter][2]= $row['Vorname'];
        $matrixr[$counter][3]= $row['Name'];
        $matrixr[$counter][4]= $row['Jg'];
        $matrixr[$counter][5]= $row['WKname'];
        $matrixr[$counter][6]= $row['Ort'];
        $matrixr[$counter][7]= $row['Datum']; 
        $matrixr[$counter][8]= $row['Lauf'];

        $counter++;
        
        }
        $rmsize=$counter;
        
/*---------------------------------TEST tool-----------------------------
for ($i=0;$i<$rmsize;$i++){
    ?><p><?php echo $matrixr[$i][0];
    echo ", ";
    echo $matrixr[$i][1];
    echo ", ";
    echo $matrixr[$i][2];
    echo ", ";
    echo $matrixr[$i][3];
    echo ", ";
    echo $matrixr[$i][4];
    echo ", ";
    echo $matrixr[$i][5];
    echo ", ";
    echo $matrixr[$i][6];
    echo ", ";
    echo $matrixr[$i][7];
     echo ", ";
    echo $matrixr[$i][8];

    ?></p><?php
}       */
        
// -- Zurück verwandeln der Leistungen in Minuten und Sekunden-----
        
        for($i=0;$i<$rmsize;$i++)
        {
            if($matrixr[$i][1]>60)
            {
                if($matrixr[$i][1]<3600)
                {
                    $stime=$matrixr[$i][1];
                    $minuten=$stime/60;
                    settype($minuten, "integer");
                    $sekinmin=$minuten*60 ;
                    $sekunden=$stime-$sekinmin;
                    $sekunden=round($sekunden, 2);
                    $numsekunden=$sekunden; //Nummerischer Wert speichern bevor sekunde zu string wird
                    //Hundertstel immer auf zwei kommastellen!
                    $rundsekunden=$sekunden;
                    settype($rundsekunden, "integer");
                    $hundertstel=$sekunden-$rundsekunden;
                    $hundertstel=round($hundertstel,2);
                    if($hundertstel==0)  // Falls keine hunderstel Voranden,
                    {                     // füge hinten zwei nullen an
                        $arraysekunde=array($rundsekunden,".00");
                        $sekunden=implode("",$arraysekunde);
                    }
                    if($hundertstel==0.1 or $hundertstel==0.2 or $hundertstel==0.3 or
                       $hundertstel==0.4 or $hundertstel==0.5 or $hundertstel==0.6 or
                       $hundertstel==0.7 or $hundertstel==0.8 or $hundertstel==0.9
                       )  // falls nur eine stelle nach dem komma
                    {                     // füge hinten eine null an
                        $arraysekunde=array($sekunden,"0");
                        $sekunden=implode("",$arraysekunde);
                    }
                    // Nullen eifügen für zb. 3:05.55
                    if($sekunden<10){
                        $arraysekunde=array("0", $sekunden);
                        $sekunden=implode("", $arraysekunde);
                    }
                    $array = array($minuten, $sekunden);
                    $time = implode(":", $array);

                    $matrixr[$i][1]= $time;
                }
                else
                {
                    $stime=$matrixr[$i][1];
                    $stunden=$stime/3600;
                    settype($stunden, "integer");
                    $mininstd=$stunden*3600;
                    $stime=$stime-$mininstd;
                    $minuten=$stime/60;
                    settype($minuten, "integer");
                    $sekinmin=$minuten*60 ;
                    $sekunden=$stime-$sekinmin;
                    $sekunden=round($sekunden, 2);
                    if($sekunden<10){
                        $arraysekunde=array("0", $sekunden);
                        $sekunden=implode("", $arraysekunde);
                    }
                    if($minuten<10){
                        $arrayminute=array("0", $minuten);
                        $minuten=implode("", $arrayminute);
                    }
                    $array = array($stunden, $minuten, $sekunden);
                    $time = implode(":", $array);

                    $matrixr[$i][1]= $time;
                }
                
            }
            else
            {       //Hundertstel immer auf zwei kommastellen!
                    $sekunden=$matrixr[$i][1];
                    $rundsekunden=$sekunden;
                    settype($rundsekunden, "integer");
                    $hundertstel=$sekunden-$rundsekunden;
                    $hundertstel=round($hundertstel,2);
                    if($hundertstel==0)  // Falls keine hunderstel Voranden,
                    {                     // füge hinten zwei nullen an
                        $arraysekunde=array($rundsekunden,".00");
                        $sekunden=implode("",$arraysekunde);
                    }
                    if($hundertstel==0.1 or $hundertstel==0.2 or $hundertstel==0.3 or
                       $hundertstel==0.4 or $hundertstel==0.5 or $hundertstel==0.6 or
                       $hundertstel==0.7 or $hundertstel==0.8 or $hundertstel==0.9
                       )  // falls nur eine stelle nach dem komma
                    {                     // füge hinten eine null an
                        $arraysekunde=array($sekunden,"0");
                        $sekunden=implode("",$arraysekunde);
                    }
                    $matrixr[$i][1]=$sekunden;
            }
        }
        
   
//---------------------Löschen jener Zeilen die zuviel sind--------------
        $zeileneuematrix=0;
        for ($i=0;$i<$rmsize;$i++)
        {
            if($mleistung[$i][4]!=1)   //Dh. sie soll nicht gecancelt werde,
            //also in der BS auftauchen, daher: übertragen (wo das nicht wahr ist: nicht übertragen)
     
            {    
            
            $matrix[$zeileneuematrix][0]= $matrixr[$i][0];
            $matrix[$zeileneuematrix][1]= $matrixr[$i][1];
            $matrix[$zeileneuematrix][2]= $matrixr[$i][2];
            $matrix[$zeileneuematrix][3]= $matrixr[$i][3];
            $matrix[$zeileneuematrix][4]= $matrixr[$i][4];
            $matrix[$zeileneuematrix][5]= $matrixr[$i][5];
            $matrix[$zeileneuematrix][6]= $matrixr[$i][6];
            $matrix[$zeileneuematrix][7]= $matrixr[$i][7];
            $matrix[$zeileneuematrix][8]= $matrixr[$i][8]; 

            $zeileneuematrix++;   
            }
            
        }
        
        $msize=sizeof($matrix); //Variable für die Grösse der Matrix (anzahl Leistungen)



  


// *******************************************************************
//------Zweiter Teil der Matrix erstellen die ausgegebne Wird -------
//-----------------------technische Disziplinen!!!!!--------------------------------
// ******************************************************************

// ---------------- Doppelte Leistungen ausscheiden ----------------

//------Liste der Leistungen Mit technischen disziplinen ----
    $rraw=mysql_query("SELECT
            d.Lauf, b.Leistung, b.ID, b.DisziplinID, b.Mitglied, 
            m.Geschlecht, m.Jg, d.Disziplin, w.Jahr
            FROM bestenliste b
            INNER JOIN Disziplin d ON(b.DisziplinID=d.ID)
            INNER JOIN Wettkampf w ON(b.Wettkampf=w.ID)
            INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
            WHERE Geschlecht=$Geschlecht AND Jg>($Jahr-$Kategorieo)
            AND Jg<($Jahr-$Kategorieu) AND Jahr=$Jahr
            AND (Lauf=2 OR Lauf=3 OR Lauf=4)
            ORDER BY Lauf, Laufsort, Disziplin, Leistung DESC , ID
            ");
    if (! $rraw){
    echo"query mistake" . mysql_error();
}

    $counter=0;

    while($row=mysql_fetch_assoc($rraw)) {

        $mleistung[$counter][0]= $row['ID'];
        $mleistung[$counter][1]= $row['Leistung'];
        $mleistung[$counter][2]= $row['Mitglied'];
        $mleistung[$counter][3]= $row['DisziplinID'];
        $mleistung[$counter][4]= "NULL";           // Zeile zum markieren welche gelöscht werden

        $counter++;

        }

        $mleistungsize=$counter; //Variable für die Grösse der Matrix (anzahl Leistungen)

// --------- Zeilen wo neue Disziplinen beginnen markieren-------

    $newdisziplint=array();
    $olddisziplint=NULL;
    $j=0;

    for ($i=0;$i<$mleistungsize;$i++){
        if($mleistung[$i][3]!=$olddisziplint){
            $newdisziplint[$j]=$i;
            $j++;
        }
        $olddisziplint=$mleistung[$i][3];
    }
    $numberdisziplint=$j;

//------------- Doppelte Leistungen --------------------------------

    for ($j=0;$j<$numberdisziplint;$j++) //Iteration über disziplinen
    {

        $start= $newdisziplint[$j];
        if (($j+1)>=$numberdisziplint)    //overflow beheben
        {                                // ""
            $end=$mleistungsize;         //  ""
        }                                //   ""
                else {                           //    ""
            $end=$newdisziplint[($j+1)];  //     ""
        }

        for($i=$start;$i<$end;$i++) // Iteration über Leistungen innerhalb der Disziplin
        {
            for ($k=($i+1);$k<$end;$k++)
            {
                if($mleistung[$i][3]==$mleistung[$k][3] &&
                    $mleistung[$i][2]==$mleistung[$k][2])
                {
                    if($mleistung[$k][1]>$mleistung[$i][1])
                    {
                        $mleistung[$i][4]=1;    // 1=CANCELN!!!
                    }else{
                        $mleistung[$k][4]=1;     // 1=CANCELN!!!
                    }
                }
            }
        }
    }
//****************************************************************
//------------------- Gesamtinformationen einholen ---------------
//****************************************************************
                        
//********************************************************************
// -- Liste mit Detailinformationen zu den technische Disziplinen!!--
//********************************************************************
     $result=mysql_query("SELECT m.Vorname, m.Name, m.Jg, m.Geschlecht,
            w.Ort, w.Datum, d.Disziplin, d.Lauf, w.Jahr,
            b.Leistung, b.ID, w.WKname, d.Laufsort
            FROM bestenliste b
            INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
            INNER JOIN Wettkampf w ON(b.Wettkampf=w.ID)
            INNER JOIN Disziplin d ON(b.DisziplinID=d.ID)
            WHERE Geschlecht=$Geschlecht AND Jg>($Jahr-$Kategorieo)
            AND Jg<($Jahr-$Kategorieu) AND Jahr=$Jahr 
            AND (Lauf=2 OR Lauf=3 OR Lauf=4)
            ORDER BY Lauf, Laufsort, Disziplin, Leistung DESC, ID
            ");                   
    if (! $result){
   echo"query mistake" . mysql_error();
}

//--------------------- in matrix eintragen ---------------------
      $counter=0;

      while($row=mysql_fetch_assoc($result)) {

        $matrixr[$counter][0]= $row['Disziplin'];
        $matrixr[$counter][1]= $row['Leistung'];
        $matrixr[$counter][2]= $row['Vorname'];
        $matrixr[$counter][3]= $row['Name'];
        $matrixr[$counter][4]= $row['Jg'];
        $matrixr[$counter][5]= $row['WKname'];
        $matrixr[$counter][6]= $row['Ort'];
        $matrixr[$counter][7]= $row['Datum'];
        $matrixr[$counter][8]= $row['Lauf'];

        $counter++;

        }
        $rmtsize=$counter;
        
//-----------------ALLE ERGEBNISE Auf ZWEI NACHKOMASTELLEN -----------       
        
        for ($i=0;$i<$rmtsize;$i++)
        {
            if($matrixr[$i][8]==2 or $matrixr[$i][8]==3) // für Sprünge und Würfe
            {
                //Hundertstel immer auf zwei kommastellen!
                $leistung=$matrixr[$i][1];
                $rundleistung=$leistung;
                settype($rundleistung, "integer");
                
                $hundertstel=$leistung-$rundleistung;
                $hundertstel=round($hundertstel,2);
                if($hundertstel==0.1 or $hundertstel==0.2 or $hundertstel==0.3 or
                    $hundertstel==0.4 or $hundertstel==0.5 or $hundertstel==0.6 or
                    $hundertstel==0.7 or $hundertstel==0.8 or $hundertstel==0.9
                    )  // falls nur eine stelle nach dem komma
                    {                     // füge hinten eine null an 
                        $arrayleistung=array($leistung,"0");
                        $leistung=implode("",$arrayleistung);
                    }
          //      $hundertstel=round($hundertstel,1)    ev. nötig
                if($hundertstel==0)  // Falls keine hunderstel Voranden,
                {                     // füge hinten zwei nullen an
                    $arraysekunde=array($rundleistung,".00");
                    $leistung=implode("",$arraysekunde);
                }
                $matrixr[$i][1]=$leistung;    
            }
        }

        
//---------------------Löschen jener Zeilen die zuviel sind--------------
        
        for ($i=0;$i<$rmtsize;$i++)
        {
            if($mleistung[$i][4]!=1)   //Dh. sie soll nicht gecancelt werde,
            //also in der BS auftauchen, daher: übertragen (wo das nicht wahr ist: nicht übertragen)

            {
            $matrix[$zeileneuematrix][0]= $matrixr[$i][0];
            $matrix[$zeileneuematrix][1]= $matrixr[$i][1];
            $matrix[$zeileneuematrix][2]= $matrixr[$i][2];
            $matrix[$zeileneuematrix][3]= $matrixr[$i][3];
            $matrix[$zeileneuematrix][4]= $matrixr[$i][4];
            $matrix[$zeileneuematrix][5]= $matrixr[$i][5];
            $matrix[$zeileneuematrix][6]= $matrixr[$i][6];
            $matrix[$zeileneuematrix][7]= $matrixr[$i][7];
            $matrix[$zeileneuematrix][8]= "null";

            $zeileneuematrix++;
            }
        }

        $msize=sizeof($matrix); //Variable für die Grösse der Matrix (anzahl Leistungen)
        $beginnstaffel=$msize;
        
        
        
// *******************************************************************
//------Dritter Teil der Matrix erstellen die ausgegebne Wird -------
//-----------------------Staffeln!!!!!--------------------------------
// ******************************************************************


//****************************************************************
//------------------- Gesamtinformationen einholen ---------------
//****************************************************************

//********************************************************************
// -- Liste mit Detailinformationen zu den Staffeln!!--
//********************************************************************
     $result=mysql_query("SELECT m.Vorname, m.Name, m.Jg, m.Geschlecht,
            w.Ort, w.Datum, d.Disziplin, d.Lauf, b.DisziplinID,
            b.Leistung, b.ID, w.WKname, d.Laufsort, w.Jahr
            FROM bestenliste b
            INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
            INNER JOIN Wettkampf w ON(b.Wettkampf=w.ID)
            INNER JOIN Disziplin d ON(b.DisziplinID=d.ID)
            WHERE (Geschlecht=$Geschlecht OR Geschlecht=3) AND Jg>($Jahr-$Kategorieo)
            AND Jg<($Jahr-$Kategorieu) AND Jahr=$Jahr AND Lauf=5
            ORDER BY Lauf, Laufsort, Disziplin, Leistung 
            ");
    if (! $result){
   echo"query mistake" . mysql_error();
}

//--------------------- in matrix eintragen ---------------------
      $counter=0;

      while($row=mysql_fetch_assoc($result)) {

        $matrixr[$counter][0]= $row['Disziplin'];
        $matrixr[$counter][1]= $row['Leistung'];
        $matrixr[$counter][2]= $row['Vorname'];
        $matrixr[$counter][3]= $row['Name'];
        $matrixr[$counter][4]= $row['Jg'];
        $matrixr[$counter][5]= $row['WKname'];
        $matrixr[$counter][6]= $row['Ort'];
        $matrixr[$counter][7]= $row['Datum'];

        $counter++;

        }
        $rmtsize=$counter;
        

//--Laufzeiten (Leistung) wieder in minuten UND sekunden verwandeln---        

        for($i=0;$i<$rmtsize;$i++)
        {
            if($matrixr[$i][1]>60)
            {
                if($matrixr[$i][1]<3600)
                {
                    $stime=$matrixr[$i][1];
                    $minuten=$stime/60;
                    settype($minuten, "integer");
                    $sekinmin=$minuten*60 ;
                    $sekunden=$stime-$sekinmin;
                    $sekunden=round($sekunden, 2);
                    $numsekunden=$sekunden; //Nummerischer Wert speichern bevor sekunde zu string wird
                    //Hundertstel immer auf zwei kommastellen!
                    $rundsekunden=$sekunden;
                    settype($rundsekunden, "integer");
                    $hundertstel=$sekunden-$rundsekunden;
                    $hundertstel=round($hundertstel,2);
                    if($hundertstel==0)  // Falls keine hunderstel Voranden, 
                    {                     // füge hinten zwei nullen an
                        $arraysekunde=array($rundsekunden,".00");
                        $sekunden=implode("",$arraysekunde);                       
                    }
                    if($hundertstel==0.1 or $hundertstel==0.2 or $hundertstel==0.3 or
                       $hundertstel==0.4 or $hundertstel==0.5 or $hundertstel==0.6 or
                       $hundertstel==0.7 or $hundertstel==0.8 or $hundertstel==0.9
                       )  // falls nur eine stelle nach dem komma
                    {                     // füge hinten eine null an
                        $arraysekunde=array($sekunden,"0");
                        $sekunden=implode("",$arraysekunde);
                    }
                    // Nullen eifügen für zb. 3:05.55
                    if($numsekunden<10){
                        $arraysekunde=array("0", $sekunden);
                        $sekunden=implode("", $arraysekunde);
                    }
                    $array = array($minuten, $sekunden);
                    $time = implode(":", $array);
                    
                    $matrixr[$i][1]= $time;   
                }
                else
                {
                    $stime=$matrixr[$i][1];
                    $stunden=$stime/3600;
                    settype($stunden, "integer");
                    $mininstd=$stunden*3600;
                    $stime=$stime-$mininstd;
                    $minuten=$stime/60;
                    settype($minuten, "integer");
                    $sekinmin=$minuten*60 ;
                    $sekunden=$stime-$sekinmin;
                    $sekunden=round($sekunden, 2);
                    if($sekunden<10){
                        $arraysekunde=array("0", $sekunden);
                        $sekunden=implode("", $arraysekunde);
                    }
                    if($minuten<10){
                        $arrayminute=array("0", $minuten);
                        $minuten=implode("", $arrayminute);
                    }                    
                    $array = array($stunden, $minuten, $sekunden);
                    $time = implode(":", $array);

                    $matrixr[$i][1]= $time;
                }
            }else{
                
                //Hundertstel immer auf zwei kommastellen!
                $sekunden=$matrixr[$i][1];
                $rundsekunden=$sekunden;
                settype($rundsekunden, "integer");
                    $hundertstel=$sekunden-$rundsekunden;
                    $hundertstel=round($hundertstel,2);
                    if($hundertstel==0)  // Falls keine hunderstel Voranden,
                    {                     // füge hinten zwei nullen an
                        $arraysekunde=array($rundsekunden,".00");
                        $sekunden=implode("",$arraysekunde);
                    }
                    if($hundertstel==0.1 or $hundertstel==0.2 or $hundertstel==0.3 or
                       $hundertstel==0.4 or $hundertstel==0.5 or $hundertstel==0.6 or
                       $hundertstel==0.7 or $hundertstel==0.8 or $hundertstel==0.9
                       )  // falls nur eine stelle nach dem komma
                    {                     // füge hinten eine null an
                        $arraysekunde=array($sekunden,"0");
                        $sekunden=implode("",$arraysekunde);
                    }
                    $matrixr[$i][1]=$sekunden;
                
            }
        }



//---------------------Löschen jener Zeilen die zuviel sind--------------

        for ($i=0;$i<$rmtsize;$i++)
        {
            if($mleistung[$i][4]!=1)   //Dh. sie soll nicht gecancelt werde,
            //also in der BS auftauchen, daher: übertragen (wo das nicht wahr ist: nicht übertragen)

            {
            $matrix[$zeileneuematrix][0]= $matrixr[$i][0];
            $matrix[$zeileneuematrix][1]= $matrixr[$i][1];
            $matrix[$zeileneuematrix][2]= $matrixr[$i][2];
            $matrix[$zeileneuematrix][3]= $matrixr[$i][3];
            $matrix[$zeileneuematrix][4]= $matrixr[$i][4];
            $matrix[$zeileneuematrix][5]= $matrixr[$i][5];
            $matrix[$zeileneuematrix][6]= $matrixr[$i][6];
            $matrix[$zeileneuematrix][7]= $matrixr[$i][7];
            $matrix[$zeileneuematrix][8]= "zeilenumbruch";

            $zeileneuematrix++;
            }
        }

        $msize=sizeof($matrix); //Variable für die Grösse der Matrix (anzahl Leistungen)

//********************************************************************
//*******Für alle Disziplinen*****************************************
//********************************************************************

        for($i=0;$i<$msize;$i++)
        {   
//------------- Datum nur aus Monat und Tag Darstellen Lassen---------
            $rohdate=$matrix[$i][7];
            $date=explode("-",$rohdate);
            $datehelp=array($date[2],$date[1]);
            $datum=implode(".",$datehelp);
            $tagmonat=array($datum,".");
            $matrix[$i][7]=implode("",$tagmonat);
//------------ nur zwei Zahlen im Jahrgang anzeigen -----------------
            $rohJg=$matrix[$i][4];
            settype($rohJg,"integer");
            if($rohJg<2000){
                if($rohJg>100) {
                $Jg=$rohJg-1900;
                }
                if($rohJg<100){
                    $Jg=" ";
                }    
            }else if($rohJg>=2000 and $rohJg<2100){
                $Jg=$rohJg-2000;    
            }else{
                echo "error JG Anzeige";
            }
            if( $Jg==1 or $Jg==2 or $Jg==3 or
                $Jg==4 or $Jg==5 or $Jg==6 or
                $Jg==6 or $Jg==7 or $Jg==9 )
                {
                    $arrayJg=array("0",$Jg);
                    $Jg=implode($arrayJg);            
                }
            if ($Jg==0){
                $Jg="00";
            } 
            $matrix[$i][4]=$Jg; 

    // STAFFELN  BRAUCHEN KEINEN JAHRGANG! daher:  
            for ($j=$beginnstaffel;$j<$msize;$j++){
                $matrix[$j][4]="";      
            }
                  
        }

//******************************************************************
//-------------------Audgabe der matrix $matrix --------------------
//******************************************************************
       
//------------------Zählen der Anzahl Disziplinen-------------
        
        $anzdis=0;        // Variable zum Zählen der Disziplinen
        $diszp="Nichts";    // Variable für vergleich der Disziplinen 
        //(funktioniert nur falls die Liste in erster Priorität nach Disziplin geordnet ist)
        
        for ($i=0; $i<$msize ; $i++)  {
            if ($diszp!=$matrix[$i][0]){
                $anzdis++;
                $diszp=$matrix[$i][0];
            }      
        }
        
//--------------------Titel der Seite---------------------------          
$U="U";
$Kategorie=$Kategorieo;
if($Geschlecht==1){
    $mw="W ";
}else{
    $mw="M ";
}
if($Kategorieo==200){
    $mw="";
    $U="";
    if($Geschlecht==1){
        $Kategorie="Frauen";    
    }else{
        $Kategorie="M&auml;nner";
    }   
}

$arrayUberschrift=array("Bestenliste ",$U,$Kategorie," ",$mw,$Jahr );
$Uberschrift=implode($arrayUberschrift);            
        
    
        
        
        ?>
        
        
    

 

    <head>
   <!--  <meta charset="utf-8" /> -->
   <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    </head>
    <title>Bestenliste</title>
    
    
    <!-- Link zurück -->
    <form action="index.php">
    <input type="submit" value="Zur&uuml;ck zur Auswahl">
    </form>  
    
    <br />


<?php  //***********TOOL für export der Bestenliste in ein Textfile ************

/*

    //Write '$uberschrift'.txt file for the wanted categorie

    $myfile = fopen("$Uberschrift.txt", "w") or die("Unable to open file!");

    //Uberschrift
    $txt = "$Uberschrift\r\n";
    fwrite($myfile, $txt);

    //Titel
    $txt = "Leistung\tName\tJg\tWettkampf\tDatum\r\n";
    fwrite($myfile, $txt);

    //Disziplinen
            $ID=0;        //Variable für die Zeile der Matrix (welche Leistung ausgelesen wird)
            
            // Iteration über die Disziplinen
            
            for ($dis=1;$dis<=$anzdis;$dis++) {
                //Disziplin überschrift
                $arraytxt=array($matrix[$ID][0],"\t","\t","\t","\r\n" );
                $txt=implode($arraytxt);
                fwrite($myfile, $txt);
                
                //Anzahl Mitglieder in der aktuellen Disziplin berechnen
                $Anzmigdis=0; //Variable zum Speichern Der Anzahl Mitglieder in der aktuellen Disziplin
                $diszp=$matrix[$ID][0]; //Variable auf aktuelle Disziplin setzen
                for($i=0;$i<$msize;$i++){
                    if ($diszp==$matrix[$i][0]){
                        $Anzmigdis++;
                    }
                }
                 
                 // Iteration über mitglieder 
                for ($i=0; $i < $Anzmigdis; $i++) { 
                    $arraytxt=$arrayName = 
                        array(
                            $matrix[$i+$ID][1], "\t",
                            $matrix[$i+$ID][2]," ", $matrix[$i+$ID][3], "\t",
                            $matrix[$i+$ID][4], "\t",
                            $matrix[$i+$ID][5], "\t",
                            $matrix[$i+$ID][7], "\r\n"
                            );
                    $txt=implode($arraytxt);
                    fwrite($myfile,$txt);
                }
                $ID=($ID+$Anzmigdis);               
             }
             
    fclose($myfile); 

*/
    
?> 
    
<!-- ********************************************************* -->
<!-- *****************Von Hier An Kopieren ******************** -->
<!-- *********************************************************** --> 


    <h1><?php echo $Uberschrift; ?></h1>
     
    <table cellpadding="1">
        <thead>  
            <tr>      <!-- Ueberschriften -->
	           <th scope="col" width="87"> Disziplin/  <br />Leistung</th> 
               <th scope="col">Name</th>   
               <th scope="col">Jg</th>
               <th scope="col">Wettkampf</th>
		    <!--   <th scope="col">Ort</th>   für ausgabe des Ortes: komentar entfernen plus unten if bedingung in der iteration über spalten entfernen-->
               <th scope="col">Datum</th>
            </tr>
        </thead>                   
        
     
       
        <tbody>
        
        <?php 
        
        $ID=0;        //Variable für die Zeile der Matrix (welche Leistung ausgelesen wird)
        
        // Iteration über die Disziplinen
        for ($dis=1;$dis<=$anzdis;$dis++) {
            ?>
            
            <tr>
	            <td rowspan="1" width="90"> <b> <?php echo $matrix[$ID][0]; ?> </b> </td>
                <td rowspan="1">&nbsp;</td>
                <td rowspan="1" width="22">&nbsp;</td>
                <td rowspan="1">&nbsp;</td>
                <td rowspan="1">&nbsp;</td>
                <td rowspan="1">&nbsp;</td>
            </tr>
            <tr> <?php
            
            
            //Anzahl Mitglieder in der aktuellen Disziplin berechnen
            $Anzmigdis=0; //Variable zum Speichern Der Anzahl Mitglieder in der aktuellen Disziplin
            $diszp=$matrix[$ID][0]; //Variable auf aktuelle Disziplin setzen
            for($i=0;$i<$msize;$i++){
                if ($diszp==$matrix[$i][0]){
                    $Anzmigdis++;
                }
            }
                      
            // Iteration über Tabellenspalten
            for ($spa=1;$spa<=6;$spa++) {  // 6, da wir oben 7 elemente definiert haben und nun den Namen und den Vornamen in eine Spalte bringen
                if($spa!=5){ 
                    ?> <td rowspan="1"> <?php   
                    
                  
                    // Iteration über Mitglieder
                    for ($mig=$ID;$mig<($ID+$Anzmigdis);$mig++){
                        $aspa=$spa;
                        $aspa++;      //Spalte+1
                        if ($spa==1){       //erste spalte
                            echo $matrix[$mig][$spa];
                            ?>  <br /> <?php   
                        }
                        elseif ($spa==2) {       //Spalte mit dem Namen
                                echo $matrix[$mig][$spa];
                                ?> &nbsp;<?php
                                echo $matrix[$mig][$aspa];
                                ?>  <br /> <?php  
                        }
                        else{               //restliche Spalten
                            echo $matrix[$mig][$aspa];
                            ?>  <br /> <?php
                        }                        
                  }
                
                ?>  &nbsp; <?php
                ?> </td> <?php 
                }    
             }
             $ID=($ID+$Anzmigdis);
         ?> </tr> <?php         
         }
                
         ?>
         
        </tbody>
        
    </table>
 
    <!-- ************************************************ -->
    <!-- ************Bis Hier Kopieren******************** -->
    <!-- Link zurück -->
    <form action="index.php">
    <input type="submit" value="Zur&uuml;ck zur Auswahl"/>
    </form>  
 
    </body>        
         

        
        

 <?php 
 mysql_close($conn);
  ?>

