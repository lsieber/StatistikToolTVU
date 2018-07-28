
<?php 

    include "anzeige.php"

 ?>



<!Doctype html>

<head>

<title>Eingabe von Mehrkampfleistungen</title>

    <script type="text/javascript" src="jquery.js"></script>
  
<script type="text/javascript" >
    function post(eingabe) {

        var kat = eingabe;
        var wettkampf = "no_w";
        var whichWettkampf = document.getElementsByName("wettkampf");
        var lenW = whichWettkampf.length;
        for (i=0;i<lenW;i++)
        {
            if(whichWettkampf[i].checked)
            {
                var wettkampf = whichWettkampf[i].value;
                break;
            }
        }

        var kat = "no_k";
        var whichKategorie = document.getElementsByName("kategorie");
        var lenK = whichKategorie.length;
        for (i=0;i<lenK;i++)
        {
            if(whichKategorie[i].checked)
            {
                var kat = whichKategorie[i].value;
                break;
            }
        }

        $.post('mk_anzeige_m.php',{postkategorie:kat,postwettkampf:wettkampf},
             function(data){
             $('#result_m').html(data);
        });
        
        $.post('mk_anzeige_d.php',{postkategorie:kat},
             function(data){
             $('#result_d').html(data);
        });       
        
    }
</script>

</head>

<body>
    
    <h2>
        Eingabe Leistungen TVU Bestenliste
    </h2>
    
    <form  method="POST" action="mk_eingabe.php">
    <table border="1" cellpadding="10" cellspacing="2" summary="">
    
        <thead>   <!-- Ueberschriften -->
            <tr>
            <th scope="col">Wettkampf</th>
            <th scope="col">Kategorie</th>
            <th scope="col">Disziplin</th>
            <th scope="col">Athleten</th>
	        
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
                <input type="radio" name="kategorie" id="all" value="1000" onclick="post();" /> alle <br/>
                
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
            <input type="submit" value="Weiter" />
        </td>
    
    
    </table>
    </form>
<br />

<a href="http://localhost:1337/bestenliste/"><input type="button" value="&Uuml;bersicht"/></a>

<a href="spez_eingaben/Eingabe_a_w_d.html"><input type="button" value="Eingabe Disziplin, Wettkampf, Athlet"/></a>

<a href="http://localhost:1337/bestenliste/Ausgabe/index.php"><input type="button" value="Ausgabe"/></a>

<a target="_blank" href="http://localhost:1337/phpmyadmin/index.php?db=bestenliste&table=wettkampf&target=sql.php&token=2a3769a865d80f579906b2292ded9f90#PMAURL:db=bestenliste&server=1&target=db_structure.php&token=2a3769a865d80f579906b2292ded9f90">

<input type="button" value="phpmyadmin Datenbank"/></a>
        
</body>


