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
$kat = $_POST['kategorie'];
foreach ($kat as $value) {
    $age[] = $value-2;
    $age[] = $value-1;
}
if ($age[sizeof($age)-1] == 199) {
    array_pop($age); array_pop($age); // acctually this line is superficial. 
    $age = array_merge($age, range(18, 60));
} 
$sex = $_POST['sex'];
$year = $_POST['year'];
if ($year[0] == 0) {$year = range(1950, 2050);}
$dis = $_POST['disziplin'];

print_r($age);
print_r($age[sizeof($age)-1]);

/*
if($_POST['topf']==""){
    $ausgabe->set_top($_POST['top'],$_POST['topf']); 
}else{
    $ausgabe->set_top(1000,$_POST['topf']); 
}*/


function set_array_of_asked_jg(){
    $this->AnzKat = sizeof($kategorie);
    $this->Kategorieo = $kategorie[$this->AnzKat-1];
    if($this->Kategorieo == 200){
        if ($this->AnzKat > 1) {
            $this->Kategorieu = $kategorie[0]-3;
        }else{
            $this->Kategorieu = 17;
        }
    }elseif ($this->Kategorieo == 1000) {
        $this->Kategorieu = 0;
    }else{
        $this->Kategorieu = $kategorie[0]-3;
    }  
    if($this->Kategorieo == 10){
        $this->Kategorieu = 0;
    }
}
        

function get_bestlist_per_laufkat($laufkat)
{
    $conn= mysql_connect($host, $user, $password);
    mysql_set_charset('utf8');
    if (!$conn)
    {
        echo "Unable to connect to DB: " . mysql_error();
        exit;
    }
    if (!mysql_select_db("bestenliste")) 
    {
        echo "Unable to select bestenliste: " . mysql_error();
        exit;
    }

    $rraw=mysql_query("SELECT 
        b.Leistung,b.Mitglied, m.Vorname, m.Name, m.Jg, w.WKname, w.Ort, w.Datum,
        b.DisziplinID, d.Disziplin, d.Lauf, d.Laufsort, d.ID, w.Jahr, d.MaxVal, d.MinVal
        FROM bestenliste b
        INNER JOIN Disziplin d ON(b.DisziplinID=d.ID)
        INNER JOIN Mitglied m ON(b.Mitglied=m.ID)
        INNER JOIN Wettkampf w ON(b.Wettkampf=w.ID)
        WHERE   (
                    (
                        Jg>(w.Jahr-$this->Kategorieo)
                        AND Jg<(w.Jahr-$this->Kategorieu)
                        AND Jahr IN ($this->Jahr)
                    ) 
                OR 
                    (
                        Jahr>$this->Jahrhelp 
                        AND Jg>(w.Jahr-$this->Kategorieo)
                        AND Jg<(w.Jahr-$this->Kategorieu)
                    )
                ) 
            AND 
                ( 
                    Geschlecht=$this->Geschlecht
                    OR Geschlecht=$this->Geschlechthelp
                    OR Geschlecht=3
                )  
        ORDER BY Lauf, Laufsort, Disziplin, Leistung, ID
    ");
    // ***************Import der Daten in $leitung Matrix*******************3
    if (! $rraw)
    {
        echo"query mistake" . mysql_error();        
    } 
    $counter = 0;
    while($row=mysql_fetch_array($rraw,MYSQL_NUM)) 
    {
        $this->leistung[$counter] = $row; 
        $counter++;
    }
    mysql_close($conn);       
}




/*
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
 

*/