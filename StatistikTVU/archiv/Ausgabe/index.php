<!Doctype html>

<html>
<title>Optionen der Bestenliste</title>


<head>

<meta charset="utf-8"/>
    <!-- put this into the css file if it once will exist! -->
<link rel="stylesheet" type="text/css" href="inc/stylesheet.css">

<script type="text/javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/script.js"></script>
<!--  
<script type="text/javascript">
    function myFunction() {
        alert('houi');
    //document.getElementById("demo").innerHTML = "Hello World";
}
</script>-->


</head>

<body>

    <div class="einstellungen">
        <h3>Einstellungen zur Bestenliste</h3>   
        <form action="bestenliste.php" method="post" >


            <div class="submit">
                <input type="submit"  id="submit" value="Zur Bestenliste"  />
            </div> 

         <!-- onsubmit="validate_kategorie()" -->
            <ul class="radio">
       <!--         <li> <div class="titleradio">Kategorie</div>
                    <input type="checkbox" name="kategorie[]" id="U10" value="10" onclick='no_all_kat();eval_mit()' /> U10 <br/>
                    <input type="checkbox" name="kategorie[]" id="U12" value="12" onclick='no_all_kat();eval_mit()' /> U12 <br/>
                    <input type="checkbox" name="kategorie[]" id="U14" value="14" onclick='no_all_kat();eval_mit()' /> U14 <br/>
                    <input type="checkbox" name="kategorie[]" id="U16" value="16" onclick='no_all_kat();eval_mit()' /> U16 <br/>
                    <input type="checkbox" name="kategorie[]" id="U18" value="18" onclick='no_all_kat();eval_mit()' /> U18 <br/>
                    <input type="checkbox" name="kategorie[]" id="UE16" value="200" checked="yes" onclick='no_all_kat();eval_mit()' /> Aktiv <br/>
                    <input type="checkbox" name="kategorie[]" id="allkat" value="1000" onclick='eval_mit();all_none_kat()' /> alle <br/>  <!-- Wert der nicht erreicht wird  ->
                </li>  
                 -->
                 <li> <div class="titleradio">Kategorie</div>
                    <input type="radio" name="kategorie[]" id="allkat" value="1000" onclick='all_none_kat();eval_mit()' /> alle <br/>  <!-- Wert der nicht erreicht wird  -->
                    <input type="radio" name="kategorie[]" id="U10" value="10" onclick='no_all_kat();eval_mit()' /> U10 <br/>
                    <input type="radio" name="kategorie[]" id="U12" value="12" onclick='no_all_kat();eval_mit()' /> U12 <br/>
                    <input type="radio" name="kategorie[]" id="U14" value="14" onclick='no_all_kat();eval_mit()' /> U14 <br/>
                    <input type="radio" name="kategorie[]" id="U16" value="16" onclick='no_all_kat();eval_mit()' /> U16 <br/>
                    <input type="radio" name="kategorie[]" id="U18" value="18" onclick='no_all_kat();eval_mit()' /> U18 <br/>
                    <input type="radio" name="kategorie[]" id="UE16" value="200" checked="yes" onclick='no_all_kat();eval_mit()' /> Aktiv <br/>
                    <input type="button" name="kat2check" id="divkat" value="mehrere" onclick='kat_rad2check()'/>   <br/> 
                </li> 
                <li> <div class="titleradio">Geschlecht</div>
                    <input type="radio" name="sex" id="Female" value="1" checked="yes" onclick='eval_mit();kat_radio2checkbox()' /> Weiblich <br/>
                    <input type="radio" name="sex" id="Male" value="2" onclick='eval_mit()' /> M&auml;nnlich <br/>
                    <input type="radio" name="sex" id="allsex" value="3" onclick='eval_mit()' /> alle <br/>        
                </li>
                <li ><div class="titleradio">Jahr </div>
                    <input type="radio" name="year[]" id="allyear" value="0" onclick="all_none_year()"/> alle <br />
                    <div id="years">
                        <input type="radio" name="year[]" id="2016" value="2016" checked="yes" onclick="no_all_year()"/> 2016 <br />         
                        <input type="radio" name="year[]" id="2015" value="2015" onclick="no_all_year()"/> 2015 <br />       
                        <input type="radio" name="year[]" id="2014" value="2014" onclick="no_all_year()"/> 2014 <br />         
                    </div>
                    <input type="button" name="year2check" id="divyear" value="mehrere" onclick='year_rad2check()'/>  <br />                      
                    <input type="button" name="all_year" id="allyear" value="alle" onclick='get_years()'/>  <br />                      
                </li>
                
                <li> <div class="titleradio">Top</div>
                    <input type="radio" onclick="clear_field()" name="top" id="alltop" value="1000" checked="yes"/> alle <br />
                    <input type="radio" onclick="clear_field()" name="top" id="top5" value="5" /> top 5 <br />
                    <input type="radio" onclick="clear_field()" name="top" id="top10" value="10" /> top 10 <br />
                    <input type="radio" onclick="clear_field();all_years_check()" name="top" id="Rekorde" value="1" /> Rekorde <br />
                    top <input type="number" onfocus="clear_radio()" name="topf" id ="topnumb" min="1" max="999" size="5" onclick="clear_radio()">
                </li> 

                <li id="result_d" >
                    <div class="titleradio">Disziplin </div>
                    <input type="checkbox" name="disziplin[]" value="100000" checked="yes" onclick="eval_mit()"/> alle <br />    
                </li>
            </ul>
            
            <div class="submit">
                <input type="submit"  id="submit" value="Zur Bestenliste"  />
            </div> 

            <div id="demo" >

            </div>

                  

        </form>
    </div>

    <div class="links">
        <a href="http://localhost:1337/bestenliste/index.php"><input type="button" value="zur &Uuml;bersicht"/></a>

        <a href="http://localhost:1337/bestenliste/Eingabe/index.php"><input type="button" value="Eingabe der Leistungen"/></a>
                   
        <a target="_blank" href="http://localhost:1337/phpmyadmin/index.php?db=bestenliste&table=wettkampf&target=sql.php&token=2a3769a865d80f579906b2292ded9f90#PMAURL:db=bestenliste&server=1&target=db_structure.php&token=2a3769a865d80f579906b2292ded9f90">
        <input type="button" value="phpmyadmin Datenbank"/></a>
    </div>    
</html>