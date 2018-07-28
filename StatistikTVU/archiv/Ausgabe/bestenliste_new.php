 <!Doctype html>

<?php

//neues element der Klasse Ausgabe erstellen
include('inc/ausgabe.php');

//********************************************************************
//********** CONECTION TO DATABASE: FILL IN INFORMATION **************
$host = "localhost";    // NAME OF THE HOST: TYPE HERE      **********
$user = "root";         // USERNAME: TYPE HERE              **********
$password = "";         // PASWORD FOR DB: TYPE HERE        **********
//********************************************************************
//********************************************************************

$ausgabe = new Ausgabe();

//***********save elements from index.php into variables in class ausgabe*********
$ausgabe->set_kategorie($_POST['kategorie']);
$ausgabe->set_geschlecht($_POST['sex']);
$ausgabe->set_jahr($_POST['year']);
$ausgabe->set_disziplin($_POST['disziplin']);
if($_POST['topf']==""){
    $ausgabe->set_top($_POST['top'],$_POST['topf']); 
}else{
    $ausgabe->set_top(1000,$_POST['topf']); 
}
//***************connect to DB and set variable leistung and AnzLeiDis************
$ausgabe->set_leistung($host, $user, $password);
$ausgabe->set_AnzLeiDis($ausgabe->leistung);

//******* Check ob minimal und maximalwerte eingehalten werden **********************
$ausgabe->check_minmaxvalue();

//******************set the right order of results***************************
$ausgabe->order();

//****************set right format for different parts of leistung*****************
$ausgabe->all_second2normal();
$ausgabe->all_two_decimal();
$ausgabe->jahrgang_twonumber();

$date_format = sizeof($_POST['year']);
if ($_POST['year'][0] == 0) {
    $date_format = 2;
}
$ausgabe->date($date_format); //input: 1 for date format day.month >2 for only year

//************************Doppelte Leistungen ausscheiden************************
$ausgabe->one_leistung();

//**********************nur eine z.b Top5 auswahl**************************
$ausgabe->top();

//**********************Titel der Ausgabe**********************************
$ausgabe->set_Uberschrift();           
        
//***************ein txt file schreiben im Ordner Bestenliste********************
$ausgabe->write_textfile();

//************** Zeige nur ausgewählte Disziplinen an **********************

if (sizeof($_POST['disziplin']) > 1) {
    $ausgabe->disziplin();
}

//**********************Ausgabe in html format*******************************

?> <head>
   <!--  <meta charset="utf-8" /> -->
   <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    </head>
    <title>Bestenliste</title>

<body>
    <table>
        <div class="csc-header csc-header-n1"><h1 class="csc-firstHeader"><?php echo $ausgabe->Uberschrift; ?></h1></div>
        <!-- <h1><?php echo $ausgabe->Uberschrift; ?></h1> -->
        <thead>  
            <tr><b> 
<?php if($ausgabe->minmaxfullfilled[0] == 1)
    {   echo "Enthält disziplinen die die Maximalen oder minimalen Werte verletzen!!! "; //
    print_r($ausgabe->minmaxfullfilled);
}?>               
<?php if($ausgabe->top!=1){?><th width="120"> Disziplin/  <br />Leistung</th> <?php }
else { ?>           <th>Disziplin</th>
                    <th>Leistung</th>   <?php } ?>
                    <th>Name</th>   
                    <th>Jg</th>
<?php if($ausgabe->top!=1){?><th>Wettkampf</th>   <?php } ?>
                    <th>Datum</th>
            </b></tr>
        </thead>  
        <?php
        $ZeileBestenliste=0;    // Position in der bestenliste matrix
        for ($i=0; $i < sizeof($ausgabe->AnzLeiDis) ; $i++) 
        {   
            if ($ausgabe->top != 1) {    ?>
            <tr>
                <td><b> <?php echo($ausgabe->bestenliste[$ZeileBestenliste][9]);?></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>  <?php
            }
            for ($j=0; $j < $ausgabe->AnzLeiDis[$i][3]; $j++) 
            {   ?>
                <tr>
<?php if($ausgabe->top==1){?><td><?php echo($ausgabe->bestenliste[$ZeileBestenliste][9]);?></td> <?php } ?>
                    <td><?php echo($ausgabe->bestenliste[$ZeileBestenliste][0]);?></td>
                    <td><?php echo($ausgabe->bestenliste[$ZeileBestenliste][2]." ".$ausgabe->bestenliste[$ZeileBestenliste][3]);?></td>
                    <td><?php echo($ausgabe->bestenliste[$ZeileBestenliste][4]);?></td>
<?php if($ausgabe->top!=1){?><td><?php echo($ausgabe->bestenliste[$ZeileBestenliste][5]);?></td>  <?php } ?>
                    <td><?php echo($ausgabe->bestenliste[$ZeileBestenliste][7]);?></td>
                </tr>  <?php
                $ZeileBestenliste++;
            }
        }
        ?> 
    </table> 
</body>
<?php
 

