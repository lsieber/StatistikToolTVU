
<?php 

    include ("anzeige.php");
    
    SESSION_START();
 ?>
<!Doctype html>
<head>
<title>Eingabe von Mehrkampfleistungen</title>
    <script type="text/javascript" src="jquery.js"></script>
    <meta charset="utf-8">

</head>

<body>
<?php
//Erfassen der Einstellungen der mehrkampf startseite
$postyear = $_POST["year"]; //String (nur id des wettkampfs)
$postdisziplin = $_POST["disziplin"]; //array (mehrere ids disziplin möglich)
$postmitglied = $_POST["mitglied"]; //array (mehrere ids mitglieder möglich)

//berechnen der Anzahl disziplinen und Mitglieder die angezeigt werden
$anzahldisziplinen=sizeof($postdisziplin);
$anzahlmitglieder=sizeof($postmitglied);

//übergabe dieser daten an mk_datenbank_schreiben

$_SESSION["wettkampf"]=$postyear;
$_SESSION["disziplin"]=$postdisziplin;
$_SESSION["mitglied"]=$postmitglied;
?>

<h2>
        Eingabe Leistungen TVU Bestenliste
</h2>
    <form method="POST" action="datenbank_schreiben.php">
<!-- Infos zum gewählten Wettkampf anzeigen oberhalb der Tabelle -->
    <table border="1" cellpadding="2" cellspacing="1" summary="">
    	<tr> <?php 
            echo "Bestenliste fürs Jahr ".$postyear." eingeben";
        	?>
    	</tr>
<!-- Ueberschriften mit den Disziplinen-->	
        <thead>   
            <tr>
				<th scope="col">Disziplin</th>
            <?php
            for ($i=0;$i<$anzahldisziplinen;$i++){
            	?><th scope="col" ><?php
            	$check=0;
            	for ($j=0;$j<$mdisziplinsize;$j++){
            		if ($mdisziplin[$j][0]==$postdisziplin[$i]){
            			echo $mdisziplin[$j][1];
            			$check=1;
            		}
            	}
            	if($check==0){echo("Fehler!!");} //Fehler ausgeben falls keine übereinstimmung gefunden wurde!
            	?></th><?php
            }
            ?>
			</tr>
        </thead>

<!-- Zeilen mit den Mitgliednamen und den Eingabefeldern -->
		<tbody>
			<?php
            for ($i=0;$i<$anzahlmitglieder;$i++){
            ?>
				<tr>
<!-- Mitgliednamen-->					
					<td>
					<?php
					$check=0;
        			for ($j=0;$j<$mmitgliedsize;$j++){
        				if ($mmitglied[$j][0]==$postmitglied[$i]){
        					echo ($mmitglied[$j][2]);
        					echo (" ");
        					echo ($mmitglied[$j][1]);
        					$check=1;
        				}
        			}
        			if($check==0){echo("Fehler!!");} //Fehler ausgeben falls keine übereinstimmung gefunden wurde!
        			?> </td> 
<!-- Eingabefelder--> 
 					<?php          		
            		for ($j=0;$j<$anzahldisziplinen;$j++){
            		?>
					<td>
						<input 
                        type="text" 
                        size="7" 
                        name="<?php echo($i*$anzahldisziplinen+$j);?>" 
                        />  
                        <br/>
					</td> 
					<?php 
					} ?>
				</tr>
			<?php 
			} ?>
<!-- Absendebutton--> 
			<tr>
				<td>
				<input type="submit" value="Eingabe" />
				</td>
				
				<div id="eingabeout">
                </div>

			</tr>
		</tbody>

    </table>
    </form>
    <br />

    <a href="mehrkampf.php"><input type="button" value="Mehrkampfeingabe"/></a>
    <a href="index.php"><input type="button" value="zurück"/></a>
    <a href="http://localhost/bestenliste/"><input type="button" value="&Uuml;bersicht"/></a>

</body>