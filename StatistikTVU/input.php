<?php 
include_once("inc/class_links.inc.php"); 
$link = new link();
include_once("inc/class_sql.inc.php"); 
$sql = new sql_generator();
include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection Error</br>";}
require_once("inc/functions_display.php"); 

?>
<!Doctype html>
<head>
    <title>Eingabe der Leistungen</title>

    <script type="text/javascript" src="inc/jquery-3.2.1.js"></script>
    <!-- script zum senden der Kategorie an die files anzeige_m.php
    und anzeige_d.php damit die gewünschten disziplinen und mitgleider
    angezeigt werden -->  
    <script type="text/javascript" src="inc/script_input.js"></script>
    <!-- script zum vorbereiten der daten und zum senden dergleichen
    an eingabe.php wo die daten in die DB geschrieben werden --> 
    <style>

    table { table-layout: fixed; }
    .subject { width: 5; }
     </style>
</head>

<body onload="post(15827);insert(0,true)">
    <h2> Eingabe Leistungen TVU Bestenliste </h2>
    
    <form action="insert_.php" method="POST" >
    <table border="1" cellpadding="10" cellspacing="2" summary="">
        <thead>   <!-- Ueberschriften -->
            <tr>
                <th scope="col" class="subject" >Wettkampf</th>
                <th scope="col">Kategorie</th>
                <th scope="col">Disziplin</th>
                <th scope="col">Athlet</th>
                <th scope="col">Leistung</th>
            </tr>
        </thead>

<!-- ----------------- Wahl des Wettkampfes ---------------- -->

        <td align="left" valign="top" width="180">
        <div id="competition_list"><?php visible_competitions($conn); ?></div>
        
        </br><b>Jahr</b>
        <input type="number" id="year" min="1950" max="2049" onclick="check_wk_year(1)">
        </br></br>
        <div id="new_wettkampf_link"><a href="#"onclick="add_wettkampf()">Wettkampf hinzufügen</a></div>
        <div id="new_wettkampf_input"></div>
        </br></br>
        <a href="#" onclick="out_wk()">Leistungen Wettkampf</a>

        </td>
        
<!-- --------------Wahl der Kategorie ---------------------- -->
        
        <td align="left" valign="top" width="78">
            <form action="eingabe_m.php" method="POST" >
                <input type="radio" name="kategorie" id="U10" value="10" onclick="post();" /> U10 <br/>
                <input type="radio" name="kategorie" id="U12" value="12" onclick="post();" /> U12 <br/>
                <input type="radio" name="kategorie" id="U14" value="14" onclick="post();" /> U14 <br/>
                <input type="radio" name="kategorie" id="U16" value="16" onclick="post();" /> U16 <br/>
                <input type="radio" name="kategorie" id="woman" value="wom" onclick="post();" /> Frauen <br/>
                <input type="radio" name="kategorie" id="man" value="men" onclick="post();" /> M&auml;nner <br/>
            </form>
        </td>
        
<!-- ---------------- Wahl der Disziplin ------------------- -->      
        
        <td align="left" valign="top">
            <div id="result_d"></div> 
            <div id="new_disziplin_link"><a href="#"onclick="add_disziplin()">Disziplin hinzufügen</a></div>
            <div id="insert_disziplin_result"></div>
            <div id="new_disziplin_input"></div>
        </td>
        
<!-- ------------------ Wahl des Athleten ------------------ -->
          
        <td align="left" valign="top">
            <div id="result_m"></div> 
            <div id="new_mitglied_link"><a href="#"onclick="add_mitglied()">Mitglied hinzufügen</a></div>
            <div id="insert_mitglied_result"></div>
            
            <div id="new_mitglied_input"></div>            
        </td>
                
                
<!-- ----------------Eingabe der Leistung ------------------- -->
        
        <td >
        	<div id="change_multiple_input"><a href="#"onclick="change_multiple_input()">Mehrkampf eingeben</a></div>
            <div id="eingabeout_above"></div>

            <div id="performance_input"> <input type="text" size="10" name="performance" id="leistung" /> </div> <br/>
            <input type="button" value="Eingabe" onclick="insert(1)" />
            <div id="eingabeout_below">
                <b>Letzte 3 Eingaben</b></br>
                <?php 
   /*             $sql_last_3 = new last_3_entries();
                $result=$conn->query($sql_last_3->get_sql());
                $array_result = $result->fetch_all(MYSQLI_ASSOC);
                foreach ($array_result as $key => $performance) 
                {
                    echo $performance['Leistung'].", ".$performance['Disziplin'].", ".$performance['Vorname']." ".$performance['Name']."</br>";
                }
*/
                ?> 
            </div>
        </td>
    
    
    </table>
    </form>
<br />

<?php
$link->link_output();
$link->link_index();
$link->link_phpmyadmin();
?>
<a href="spez_eingaben/Eingabe_a_w_d.html"><input type="button" value="Eingabe Disziplin, Wettkampf, Athlet"/></a>

<a href="mehrkampf.php"><input type="button" value="Eingabe Mehrkampf"/></a>

  

<div id="result_wk" align="left" valign="top"> </div>      
</body>


