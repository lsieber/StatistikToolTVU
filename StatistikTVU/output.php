<?php 
include_once("inc/class_links.inc.php"); 
$link = new link();
?>

<!Doctype html>

<html>
<title>Optionen der Bestenliste</title>


<head>

<meta charset="utf-8"/>
    <!-- put this into the css file if it once will exist! -->
<link rel="stylesheet" type="text/css" href="inc/stylesheet.css">

<script type="text/javascript" src="inc/jquery-3.2.1.js"></script> 
<script type="text/javascript" src="inc/script_output.js"></script>
<!--  
<script type="text/javascript">
    function myFunction() {
        alert('houi');
    //document.getElementById("demo").innerHTML = "Hello World";
}
</script>-->


</head>

<body onload="get_years_from_db()">

    <div class="einstellungen">
        <h3>Einstellungen zur Bestenliste</h3>   
        <form action="best_list.php" method="post" >


            <div class="submit">
                <input type="submit"  id="submit" value="Zur Bestenliste"  />
            </div> 

         <!-- onsubmit="validate_kategorie()" -->
            <ul class="radio">

                 <li> <div class="titleradio">Kategorie</div>
                    <input type="radio" name="kategorie[]" id="allkat" value="all" onclick='all_none_kat();eval_mit()' /> alle <br/>  <!-- Wert der nicht erreicht wird  -->
                    <input type="radio" name="kategorie[]" id="U10" value="10" onclick='no_all_kat();eval_mit()' /> U10 <br/>
                    <input type="radio" name="kategorie[]" id="U12" value="12" onclick='no_all_kat();eval_mit()' /> U12 <br/>
                    <input type="radio" name="kategorie[]" id="U14" value="14" onclick='no_all_kat();eval_mit()' /> U14 <br/>
                    <input type="radio" name="kategorie[]" id="U16" value="16" onclick='no_all_kat();eval_mit()' /> U16 <br/>
                    <input type="radio" name="kategorie[]" id="U18" value="18" onclick='no_all_kat();eval_mit()' /> U18 <br/>
                    <input type="radio" name="kategorie[]" id="UE16" value="aktiv" checked="yes" onclick='no_all_kat();eval_mit()' /> Aktiv <br/>
                    <input type="button" name="kat2check" id="divkat" value="mehrere" onclick='kat_rad2check()'/>   <br/> 
                </li> 
                <li> <div class="titleradio">Geschlecht</div>
                   <input type="radio" name="sex" id="allsex" value="all" onclick='eval_mit()' /> alle <br/>  
                    <input type="radio" name="sex" id="Female" value="1" checked="yes" onclick='eval_mit()' /> Weiblich <br/>
                    <input type="radio" name="sex" id="Male" value="2" onclick='eval_mit()' /> M&auml;nnlich <br/>
                </li>
                <li ><div class="titleradio">Jahr </div>
                    <input type="radio" name="year[]" id="allyear" value="all" onclick="all_none_year()"/> alle <br />
                    <div id="years">
                        <input type="radio" name="year[]" id="2016" value="2016" checked="yes" onclick="no_all_year()"/> 2016 <br />         
                        <input type="radio" name="year[]" id="2015" value="2015" onclick="no_all_year()"/> 2015 <br />       
                        <input type="radio" name="year[]" id="2014" value="2014" onclick="no_all_year()"/> 2014 <br />         
                    </div>
                    <input type="button" name="year2check" id="divyear" value="mehrere" onclick='year_rad2check()'/>  <br />                      
                    <input type="button" name="all_year" id="allyear" value="alle" onclick='get_years()'/>  <br /> 
                    <input type="button" name="existing_wettkampf_year" id="existing_wettkampf_year" value="get wettkämpfe" onclick='get_wettkampf_year()'/>  <br />                      
                </li>
                
                <li> <div class="titleradio">Top</div>
                    <input type="radio" onclick="clear_field()" name="top" id="alltop" value="1001" checked="yes"/> alle <br />
                    <input type="radio" onclick="clear_field()" name="top" id="top5" value="5" /> top 5 <br />
                    <input type="radio" onclick="clear_field()" name="top" id="top10" value="10" /> top 10 <br />
                    <input type="radio" onclick="clear_field();all_years_check()" name="top" id="Rekorde" value="record" /> Rekorde <br />
                    top <input type="number" onfocus="clear_radio()" name="topf" id ="topnumb" min="1" max="999" size="5" onclick="clear_radio()">
                </li> 

                <li id="result_d" >
                    <div class="titleradio">Disziplin </div>
                    <input type="checkbox" name="disziplin[]" value="all" checked="yes" onclick="eval_mit()"/> alle <br />    
                </li>
                <li>
                    <div class="titleradio">Create .txt file </div>
                    <input type="checkbox" name="txtfile[]" value="create_txt" checked="yes" onclick="eval_mit()"/> create <br />    
                </li>
                <li>
                    <div class="titleradio">Export htmlcode for website </div>
                    <input type="checkbox" name="html_code[]" value="create_html" checked="yes" onclick="eval_mit()"/> create <br />    
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
        <?php
            $link->link_index();
            $link->link_phpmyadmin();
            $link->link_input();
        ?>
    </div>    
</html>