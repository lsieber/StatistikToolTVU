
<?php 

    include ("anzeige.php");
    
    SESSION_START();
 ?>



<!Doctype html>

<head>

<title>Eingabe von Mehrkampfleistungen</title>

    <script type="text/javascript" src="jquery.js"></script>

<!-- script zum vorbereiten der daten und zum senden dergleichen
an eingabe.php wo die daten in die DB geschrieben werden --> 
<script type="text/javascript">

    function insert(){

/* --------------- Gewählte Disziplin ermitteln --------------- */        
/*        var whichDisziplin = document.getElementsByName("disziplin");
        var lenD = whichDisziplin.length;
        
        for (i=0;i<lenD;i++)
        {
            if(whichDisziplin[i].checked)
            {
                var disziplin = whichDisziplin[i].value;
                break;   
            }
            else
            {
                var disziplin = "no_d";    
            }  
        } 
        
/* --------------- Gewählten Wettkampf ermitteln --------------- */        
/*       var whichWettkampf = document.getElementsByName("wettkampf");
        var lenW = whichWettkampf.length;
        
        for (i=0;i<lenW;i++)
        {
            if(whichWettkampf[i].checked)
            {
                var wettkampf = whichWettkampf[i].value;
                break;
            }
            else
            {
                var wettkampf = "no_w";
            }
        }
        
        
/* ---------------- gewähltes Mitglied ermitteln ------------------*/
/*        var whichMitglied = document.getElementsByName("mitglied");
        var lenM = whichMitglied.length;
        if (lenM==0){
            var mitglied = "no_k";
        }else{
            for (i=0;i<lenM;i++)
            {
                if(whichMitglied[i].checked)
                {
                    var mitglied = whichMitglied[i].value;
                    break;
                }
                else
                {
                    var mitglied = "no_m";
                }
            } 
        }    
/* ------------------- eingegebene Leistung ermittlen -------------*/
/*        var leistung = $('#leistung').val();
        if(leistung==""){
            leistung="no_l";
        }


/* ---------------- senden der Daten an eingabe.php ----------- */ 

//dummy try

	var wettkampf = 9;
	var disziplin = 18;
	var mitglied = 86;
	var leistung = 5;



        $.post('eingabe.php',
            {postdisziplin:disziplin, 
            postwettkampf:wettkampf,
            postmitglied:mitglied,
            postleistung:leistung},
        function(data){
         $('#eingabeout').html(data);
        });
         
    }


</script>  

</head>

<body>

<?php
//Erfassen der Einstellungen der mehrkampf startseite
$postwettkampf=$_POST["wettkampf"]; //String (nur id des wettkampfs)
$postdisziplin=$_POST["disziplin"]; //array (mehrere ids disziplin möglich)
$postmitglied=$_POST["mitglied"]; //array (mehrere ids mitglieder möglich)

//berechnen der Anzahl disziplinen und Mitglieder die angezeigt werden
$anzahldisziplinen=sizeof($postdisziplin);
$anzahlmitglieder=sizeof($postmitglied);

//übergabe dieser daten an mk_datenbank_schreiben
$_SESSION["wettkampf"]=$postwettkampf;
$_SESSION["disziplin"]=$postdisziplin;
$_SESSION["mitglied"]=$postmitglied;
?>

<h2>
        Eingabe Leistungen TVU Bestenliste
</h2>
    <form method="POST" action="mk_datenbank_schreiben.php">
<!-- Infos zum gewählten Wettkampf anzeigen oberhalb der Tabelle -->
    <table border="1" cellpadding="2" cellspacing="1" summary="">
    	<tr> <?php 
    		$check=0;
        	for ($j=0;$j<$mwettkampfsize;$j++){
        		if ($mwettkampf[$j][0]==$postwettkampf){
        			echo $mwettkampf[$j][1];
        			$check=1;
        		}
        	}
        	if($check==0){echo("Fehler!!");} //Fehler ausgeben falls keine übereinstimmung gefunden wurde!
        	?>
    	</tr>
<!-- Ueberschriften mit den Disziplinen-->	
        <thead>   
            <tr>
				<th scope="col">Disziplin</th>
            <?php
            for ($i=0;$i<$anzahldisziplinen;$i++){
            	?><th scope="col"><?php
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
    <a href="index.php"><input type="button" value="Startseite"/></a>

</body>