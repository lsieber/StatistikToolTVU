<!Doctype html>
<head>
    <title>Bestenliste TVU</title>
</head>
<body>

<?php 
include_once("inc/class_links.inc.php"); 
$link = new link();
include_once("inc/class_sql.inc.php"); 
$sql = new sql_generator();
include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection Error</br>";}
include_once("inc/class_best_list.inc.php");

$sql->set_select('Leistung, Vorname, Name, Jg, Ort, Datum,  Disziplin, Mitglied, DisziplinID');
$sql->set_from('bestenliste b');

$sql->add_statement_INNER_JOIN("mitglied m", "b.Mitglied = m.ID");
$sql->add_statement_INNER_JOIN("wettkampf w","b.Wettkampf = w.ID");
$sql->add_statement_INNER_JOIN("disziplin d","b.DisziplinID = d.ID");

$sql->statement_sex($_POST['sex']);
$sql->set_where_year($sql->statement_IN_array($_POST['year'], 'Jahr', 'AND'));
$sql->set_where_dis($sql->statement_IN_array($_POST['disziplin'], 'Disziplin', 'AND'));

for ($i=1; $i < 7; $i++) 
{ 
    $lauf =$i;
    $lauf_true = ($lauf==1 OR $lauf == 5);
    $not_merkampf = ($lauf!=4 AND $lauf != 6);
    if ($i<=4){ //For single events the age of the People is dependant on the year and the kategorie.
        $sql->statement_age_array($_POST['kategorie']);
    }else{ //For Team events the year has to be equal to the age (Jg). This information is stored together with the sex and the kategory in the Mitglied ID of the Team: ID = 12345 means: 1: placeholder, 2:sex: 3=female, 4=male, 5=mixed, 3:kagegorie:"1"=>"10", "2"=>"12", "3"=>"14","4"=>"16","5"=> "18", "6"=>"20", "7"=>"aktiv", 45: year from 1950 onwards; year-1950 = 45.
        $MitgliedIDs = array();
        $kat_dic =array("1"=>"10", "2"=>"12", "3"=>"14","4"=>"16","5"=> "18", "6"=>"20", "7"=>"aktiv");
        $kat_dic_inv = array_flip($kat_dic);
        foreach ($_POST["kategorie"] as $kat_key => $kat) {
            print_r($_POST['year']);
            $years = ($_POST["year"][0] == "all") ? range(1950, 2049):  $_POST["year"];
            foreach ( $years as $jahr_key => $jahr) {
                $sex_array = ($_POST["sex"] == "all") ? array(3,4,5) : array($_POST["sex"]+2,5) ;  
                foreach ($sex_array as $sex_key => $sex) {
                    print_r($sex);
                    print_r($jahr);
                    $id = 10000 + 1000*$sex + 100*$kat_dic_inv[$kat] + $jahr-1950;
                    array_push($MitgliedIDs, $id);
                }
            }
        }
        $sql->set_where_age("");
        $sql->set_where_mitglied($sql->statement_IN_array($MitgliedIDs, 'Mitglied', 'AND'));
    }
    $st_lauf = $sql->statement_equal_value($lauf, 'Lauf');
    $sql->set_where_lauf($st_lauf);
    $sql->combine_where("WHERE"); // combines all previous where statements into one WHERE statement which is stored in the class and used for the final SQL querry
    $asc_desc = $lauf_true? " ASC" : " DESC";
    $sql->set_order('Lauf, Laufsort, Disziplin, Leistung'.$asc_desc.', Datum, DisziplinID, b.ID');
    $sql->set_group_by(array("DisziplinID, Mitglied"));
    //print out the sql code for each lauf
    //echo "SQL code Sent to DB for".$lauf.": ->>>>>".$sql->get_sql()."<<<<----</br>";
    $result = $conn->query($sql->get_sql());
    $array_result = $result->fetch_all(MYSQLI_ASSOC);

    $best_list_one_lauf = new best_list($array_result);
    if(!empty($best_list_one_lauf->get_raw()))
    {
        ($i>4)? :$best_list_one_lauf->one_per_mit_and_dis();
        $top = ($_POST['topf']>0) ? $_POST['topf']:$_POST['top'];
        $best_list_one_lauf->top($top);

        if ($not_merkampf) {
            $best_list_one_lauf->format_values_two_digits(); //nesscesary as well for times!!!
        }
        if ($lauf_true) {
            $best_list_one_lauf->format_values_time();
        }
        (sizeof($_POST['year'])>1)? $best_list_one_lauf->date("j.n.Y") : $best_list_one_lauf->date("j.n");

        if ($lauf == 1 OR empty($best_list->get_list())){
            $best_list = $best_list_one_lauf;
        } else {
            $best_list->add_list($best_list_one_lauf);
        }
    }  
}
$columns = ['Leistung', 'Vorname', 'Name', 'Jg', 'Ort', 'Datum'];
$columns_records = ['Vorname', 'Name', 'Jg', 'Leistung', 'Datum'];

$top = (isset($_POST['top']))?$_POST['top']:$_POST['topf'];
$title = (!isset($best_list))? "Bestenliste TVU" : $best_list->create_best_list_title($_POST["sex"],$_POST["kategorie"],$_POST["year"],$top);
$out = (!isset($best_list))? print_r("keine Leistungen"):$best_list->create_html_list($columns, $columns_records);

// Show HTML Code to Copy into other Website
if (isset($_POST["html_code"])){
    echo("<xmp>"."<div class='csc-header csc-header-n1'><h1 class='csc-firstHeader'>".$title."</h1><p>".date('d.m.Y')."</p></div>".$out."</xmp>");
    echo("</br>");
    echo("</br>");
}


// Create A TXT File for the usage in other programs (tab deliminated)
if (isset($_POST["txtfile"])) {
    if (sizeof($_POST["kategorie"])==1 && sizeof($_POST["year"]==1) && $top=='1001') {
        $stufe[0] = "Bestenlisten/EinJahrEineKategorie";
        $stufe[1] = implode("/",[$stufe[0],$_POST["year"][0]]);
        $stufe[2] = implode("/",[$stufe[1],$_POST["kategorie"][0]]);
        if(!is_dir($stufe[2])){
            if(!is_dir($stufe[1])){
                if(!is_dir($stufe[0])){
                    mkdir($stufe[0]);
                }
                mkdir($stufe[1]);
            }
            mkdir($stufe[2]);
        }
        $path = $stufe[2]."/".$title.".txt";
    }else{
        $path = "Bestenlisten/Andere Bestenlisten/".$title.".txt";
    }
    $result = $best_list->create_plain_txt($path);
    echo($result);
}


$link->link_output();
$link->link_index();


// Print out the whole Bestenliste
echo("<div class='csc-header csc-header-n1'><h1 class='csc-firstHeader'>".$title."</h1><p>".date('d.m.Y')."</p></div>".$out);


?>


<?php
$link->link_output();
$link->link_index();
?>    

</body>

