<?php 

//----------------- includes --------------------------------------
     include('anzeige.php');
     SESSION_START();
// ----------------- Erfassen der Daten aus der "mk_eingabe.php" datei ---------



$wettkampf=$_SESSION["wettkampf"];
echo $wettkampf;
$disziplin=$_SESSION["disziplin"];
$mitglied=$_SESSION["mitglied"];

$disziplinsize=sizeof($disziplin);
$mitgliedsize=sizeof($mitglied);
$anzahlleistungen=$disziplinsize*$mitgliedsize;

for($i=0;$i<$anzahlleistungen;$i++){
    $leistung[$i]=$_POST[$i];
    if($leistung[$i]==""){
        $leistung[$i]="no_l";
    } 
}


?>
<!Doctype html>
<head>

</head>
<body>
<div>
<p>hier kommen die resultate!</p>
</div>
</body>

<?php 


    
//**************************************************************
//                  EINGABE IN DANTENBANK
//**************************************************************
$counter=0;
$anzahlleerstellen=0;
$dummy=0;
$Wettkampf=$wettkampf;
for($k=0;$k<$mitgliedsize;$k++){
    $Mitglied=$mitglied[$k];
    for($j=0;$j<$disziplinsize;$j++){
        $Disziplin=$disziplin[$j];
        $Leistung=$leistung[$counter];       
        $counter=$counter+1;
        for($n=0;$n<$mdisziplinsize;$n++)
        {
            if($Disziplin==$mdisziplin[$n][0])
            {
                $Lauf=$mdisziplin[$n][2];
            }
        }
        if($Lauf==1 or $Lauf==5 )     //Für Läufe und Staffel
        {
            $Leistunghelp=$Leistung;
            if($Leistunghelp=="no_l"){ $dummy=$dummy+1; }
            else
            {
                $teile = explode(":", $Leistunghelp);
                $teilesize= sizeof($teile);
                
                if($teilesize==1) //keine Minuten
                {
                    $Leistung=$Leistunghelp;
                }
                else if($teilesize==2)
                {
                    $Leistung=$teile[0]*60+$teile[1];  
                }
                else if($teilesize==3)
                {
                    $Leistung=$teile[0]*3600+$teile[1]*60+$teile[2];
                }     
                else
                {
                    echo "syntaxfehler!!!!" ;
                }            
            }
        }

        if($Leistung=="no_l"){
            $anzahlleerstellen=$anzahlleerstellen+1;
        }else{
            $sql="INSERT INTO bestenliste VALUES ('Null','$Mitglied','$Wettkampf','$Disziplin','$Leistung')" ;
            $query = mysql_query ($sql);
        }        
    }
}
echo("Anzahl Leere Eingaben:");
echo($anzahlleerstellen);

?>


<!--   Die Ausgabe der Eingegebenen Leistungen als Kontrolle -->

<table border="1">
    <thead>
        <th>
  <?php 
        $counter=0;
        for($j=0;$j<$mwettkampfsize;$j++){
            if($Wettkampf==$mwettkampf[$j][0])
            {echo($mwettkampf[$j][1]);}
        }
        ?> 
        </th>

        <?php 
        for($i=0;$i<$disziplinsize;$i++){
            for($j=0;$j<$mdisziplinsize;$j++){
                if($disziplin[$i]==$mdisziplin[$j][0]){
                    ?> <th> <?php
                    echo($mdisziplin[$j][1]);
                    ?> </th> <?php
                }
            }
        }
        ?>   
    </thead>
    <?php 
    for($i=0;$i<$mitgliedsize;$i++){
        ?> <tr> <?php
        for($j=0;$j<$mmitgliedsize;$j++){
            if($mitglied[$i]==$mmitglied[$j][0]){
                ?> <td> <?php
                echo($mmitglied[$j][2]);
                echo " ";
                echo($mmitglied[$j][1]);
                ?> </td> <?php    
            }    
        }
        for($j=0;$j<$disziplinsize;$j++){
            ?> <td> <?php
            echo($leistung[$counter]);
            ?> </td> <?php
            $counter++;
        }
        ?> </tr> <?php   
    }
?>
</table>
<br />
<a href="mehrkampf.php"><input type="button" value="Mehrkampfeingabe"/></a>
<a href="index.php"><input type="button" value="Startseite"/></a>

