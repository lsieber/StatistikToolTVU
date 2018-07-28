<?php

//***************************
//****WICHTIG**************
//***************************
//Jahr wählen:

$jahr=2013;


//-------------- Daten aus der Datenbank holen ------------------
    //Verbindung zur Datenkbank herstellen
    include('connect.inc.php');
    
//-----Liste der Wettkämpfe------
    $rwettkampf=mysql_query(      //r steht für result
            "SELECT * FROM wettkampf
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

        $counter++;
    }
    $mwettkampfsize=$counter; // Anzahl der Wettkämpfe=Grösse Matrix
    
   
//----Liste der Disziplinen------
   $rdisziplin=mysql_query(
            "SELECT * FROM disziplin ORDER BY Disziplin ");
    if (! $rdisziplin){
    echo"query mistake" . mysql_error();
    }
    
    //Eintragen der Disziplinen in Matrix $mdisziplin
    $counter=0;
    $mdisziplin=array();   //m steht für matrix

    while($row=mysql_fetch_assoc($rdisziplin)) {

        $mdisziplin[$counter][0]= $row['ID'];
        $mdisziplin[$counter][1]= $row['Disziplin'];

        $counter++;
    }
    $mdisziplinsize=$counter; // Anzahl der Disziplinen=Grösse Matrix

   
    
 ?>
 
<!-------------------- Benutzeroberflaeche ---------------------->
<!Doctype html >
<html>
    <head>
        <title>Leistung Erfassung</title>
    
    </head>

    <body>
        <h3>Leistung Erfassung</h3>
        
            
        
        
            <table border="1"> <!-- Rahmen -->
            
            <thead>   <!-- Ueberschriften -->
                <tr>
                <th scope="col">Kategorie</th>
                <th scope="col">Disziplin</th>
                <th scope="col">Athlet</th>
                <th scope="col">Leistung</th>
	            <th scope="col">Wettkampf</th>
                </tr>
            </thead>
            
            <tbody>
                <form method="POST">
                <td>  <!--Spalte fuer Kategorie  -->
                
                    <input type="radio" name="kategorie" value="10" /> U10 <br>
                    <input type="radio" name="kategorie" value="12" /> U12 <br/>
                    <input type="radio" name="kategorie" value="14" /> U14 <br/>
                    <input type="radio" name="kategorie" value="16" /> U16 <br/>
                    <input type="submit" name="submit" value="W&auml;hle Kategorie"/>
                    
                </form>
<?php 
//------------Liste der Athleten in gewählter Kategorie--------------

    if(isset($_POST['kategorie'])) {
        $kategorie=$_POST['kategorie'];

    }

    $rmitglied=mysql_query(
            "SELECT * FROM mitglied
             WHERE Jg>($jahr-$kategorie)
             ORDER BY Vorname ");
    if (! $rmitglied){
    echo"query mistake" . mysql_error();
    }

    //Eintragen der Disziplinen in Matrix $mdisziplin
    $counter=0;
    $mmitglied=array();   //m steht für matrix

    while($row=mysql_fetch_assoc($rmitglied)) {

        $mmitglied[$counter][0]= $row['ID'];
        $mmitglied[$counter][1]= $row['Name'];
        $mmitglied[$counter][2]= $row['Vorname'];

        $counter++;
    }
    $mmitgliedsize=$counter; // Anzahl der Mitglieder in der Kategorie=Grösse Matrix
    
?>               

                <form action="eingabe.php" method="POST" >
                
                <td>  <!--Spalte fuer Disziplin -->
                <?php
                    for ($i=0;$i<$mdisziplinsize;$i++){
                        ?>  <input
                            type="radio"
                            name="disziplin"
                            value="<?php echo $mdisziplin[$i][0] ?>" >
                            <?php echo $mdisziplin[$i][1];?>
                            <br>
                            <?php
                    }
                ?>
                </td>
                
                
                <td>  <!--Spalte fuer Athlet  -->
                <?php
                    for ($i=0;$i<$mmitgliedsize;$i++){
                        ?>  <input
                            type="radio"
                            name="mitglied"
                            value="<?php echo $mmitglied[$i][0] ?>" >
                            <?php echo $mmitglied[$i][2];?> &nbsp;
                            <?php echo $mmitglied[$i][1];?>
                            <br>
                            <?php
                    }
                ?>

                </td>
            
                 
               
                
                <td>  <!--Spalte fuer Leistung  -->
                    <input type="text" name="Leistung" size="25" />
                    <input type="submit" value="Absenden" />
                
                </td>
                
                
                <td> <!--Spalte fuer Wettkampf  -->
                <?php
                    for ($i=0;$i<$mwettkampfsize;$i++){
                        ?>  <input
                            type="radio"
                            name="wettkampf"
                            value="<?php echo $mwettkampf[$i][0] ?>" />
                            <?php echo $mwettkampf[$i][1];?> , &nbsp;
                            <?php echo $mwettkampf[$i][2];?> , &nbsp;
                            <?php echo $mwettkampf[$i][3];?>
                            <br/>
                            <?php
                    }
                ?>
                </td>
                
                
                </form>
            </tbody>
            </table>
            
        
    </body>
</html>