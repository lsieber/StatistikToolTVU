<?php include "anzeige.php" ?>

<!Doctype html>
<head>
    <title>Eingabe der Leistungen</title>
    <script type="text/javascript" src="jquery.js"></script>
    <!-- script zum senden der Kategorie an die files anzeige_m.php
    und anzeige_d.php damit die gewünschten disziplinen und mitgleider
    angezeigt werden -->  
    <script type="text/javascript" src="inc/script.js"></script>
    <!-- script zum vorbereiten der daten und zum senden dergleichen
    an eingabe.php wo die daten in die DB geschrieben werden --> 
</head>

<body>
    <h2> Eingabe Leistungen TVU Bestenliste </h2>
    
    <form action="eingabe.php" method="POST" >
    <table border="1" cellpadding="10" cellspacing="2" summary="">
        <thead>   <!-- Ueberschriften -->
            <tr>
                <th scope="col">Wettkampf</th>
                <th scope="col">Kategorie</th>
                <th scope="col">Disziplin</th>
                <th scope="col">Athlet</th>
                <th scope="col">Leistung</th>
            </tr>
        </thead>

<!-- ----------------- Wahl des Wettkampfes ---------------- -->

        <td align="left" valign="top" width="180">
            <?php
                    for ($i=0;$i<$mwettkampfsize;$i++){
                        ?>  <input
                            type="radio"
                            name="wettkampf"
                            onclick="post();"
                            value="<?php echo $mwettkampf[$i][0] ?>" />
                            <?php echo $mwettkampf[$i][1];?> , &nbsp;
                            <?php echo $mwettkampf[$i][2];?> , &nbsp;
                            <?php echo $mwettkampf[$i][3];?>
                            <br/>
                            <?php
                    }
                ?>
            <input type="button" value="Ausgabe Leistungen dieses Wettkmampf" onclick="out_wk()" />

        </td>
        
<!-- --------------Wahl der Kategorie ---------------------- -->
        
        <td align="left" valign="top" width="78">
            <form action="eingabe_m.php" method="POST" >
                <input type="radio" name="kategorie" id="U10" value="10" onclick="post();" /> U10 <br/>
                <input type="radio" name="kategorie" id="U12" value="12" onclick="post();" /> U12 <br/>
                <input type="radio" name="kategorie" id="U14" value="14" onclick="post();" /> U14 <br/>
                <input type="radio" name="kategorie" id="U16" value="16" onclick="post();" /> U16 <br/>
                <input type="radio" name="kategorie" id="woman" value="201" onclick="post();" /> Frauen <br/>
                <input type="radio" name="kategorie" id="man" value="202" onclick="post();" /> M&auml;nner <br/>
            </form>
        </td>
        
<!-- ---------------- Wahl der Disziplin ------------------- -->      
        
        <td id="result_d" align="left" valign="top"> 
            <p>Kategorie w&auml;hlen</p>
        </td>
        
<!-- ------------------ Wahl des Athleten ------------------ -->
          
        <td id="result_m" align="left" valign="top">
            
            <p>Kategorie w&auml;hlen</p>
            
        </td>
                
                
<!-- ----------------Eingabe der Leistung ------------------- -->
        
        <td >
        
            <input type="text" size="10" id="leistung" />  <br/>
            <input type="button" value="Eingabe" onclick="insert()" />
            <div id="eingabeout">
                
            </div>
        </td>
    
    
    </table>
    </form>
<br />

<a href="http://localhost:1337/bestenliste/"><input type="button" value="&Uuml;bersicht"/></a>

<a href="spez_eingaben/Eingabe_a_w_d.html"><input type="button" value="Eingabe Disziplin, Wettkampf, Athlet"/></a>

<a href="mehrkampf.php"><input type="button" value="Eingabe Mehrkampf"/></a>

<a href="http://localhost:1337/bestenliste/Ausgabe/index.php"><input type="button" value="Ausgabe"/></a>

<a target="_blank" href="http://localhost:1337/phpmyadmin/index.php?db=bestenliste&table=wettkampf&target=sql.php&token=2a3769a865d80f579906b2292ded9f90#PMAURL:db=bestenliste&server=1&target=db_structure.php&token=2a3769a865d80f579906b2292ded9f90">
<input type="button" value="phpmyadmin Datenbank"/></a>
  

<div id="result_wk" align="left" valign="top"> </div>      
</body>


