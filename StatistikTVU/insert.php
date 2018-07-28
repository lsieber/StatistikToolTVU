<?php 

include_once("inc/class_links.inc.php"); 
$link = new link();
include_once("inc/class_sql.inc.php"); 
include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection error</br>";}
include_once("inc/class_best_list.inc.php");
require_once("inc/functions_display.php"); 
require_once("inc/TimeUtils.php"); 


$wettkampf_ok = ($_POST['postwettkampf']=="no_w")? FALSE: TRUE;
$wettkampf_ok ? $wettkampf = intval($_POST['postwettkampf']): $_POST['postinsert_outside_range']==0? :print_r("Wettkampf waehlen</br>");

if ($wettkampf_ok) {
    $disziplin_ok = ($_POST['postdisziplin']=="no_d")? FALSE: TRUE;
    $disziplin_ok ? $disziplin = intval($_POST['postdisziplin']): $_POST['postinsert_outside_range']==0? :print_r("Disziplin waehlen</br>");
    $mitglied_ok = ($_POST['postmitglied']=="no_m")? FALSE: TRUE;
    $mitglied_ok ? $mitglied = intval($_POST['postmitglied']): $_POST['postinsert_outside_range']==0? :print_r("Mitglied waehlen</br>");
}

$leistung_ok = ($_POST['postleistung']=="no_l")? FALSE: TRUE;
$leistung_ok ? $Leistung = $_POST['postleistung']: $_POST['postinsert_outside_range']==0? :print_r("Leistung eingeben</br>");

if ($wettkampf_ok && $disziplin_ok && $mitglied_ok && $leistung_ok) {
    $sql_chosen_dis = new sql_generator();
    $sql_chosen_dis->set_from("disziplin");
    $sql_chosen_dis->set_where_2($sql_chosen_dis->statement_equal_value($disziplin, "ID"));
    $sql_chosen_dis->combine_where();
    $result=$conn->query($sql_chosen_dis->get_sql());
    $db_info_dis = $result->fetch_all(MYSQLI_ASSOC);

    if ($db_info_dis[0]['Lauf'] == 1 OR $db_info_dis[0]['Lauf'] == 5 )
    {
        $performance = floatval(time2seconds($Leistung));
    } 
    else 
    {
        if ($db_info_dis[0]['Lauf'] == 4 OR $db_info_dis[0]['Lauf'] == 6) //mehrkampf
        { 
            $performance = intval($Leistung);
        } else //technische disziplin
        { 
            $performance = floatval($Leistung);
        }
    }

    //check min max val
    if (($performance < $db_info_dis[0]['MinVal'] OR $performance > $db_info_dis[0]['MaxVal'])AND $_POST['postinsert_outside_range']!=2) {
        echo "-----";
        echo("1");
        echo "-----";
        echo "-----";
        echo "Disziplin:".$db_info_dis[0]['Disziplin'].": Wert entspricht nicht den minimal (".$db_info_dis[0]['MinVal'].") und maximal (".$db_info_dis[0]['MaxVal'].") Bedingungen der Disziplin";
        echo("-----");
        echo "</br><input type='button' value='Trotzdem Eingeben' onclick='insert(2)' /></br>";
    } else{
        echo "-----";
        $sql="INSERT INTO bestenliste VALUES ('Null','$mitglied','$wettkampf','$disziplin','$performance')" ;
        $result=$conn->query($sql);
        echo("0");
        echo("-----");
        ($result==1)?print_r('Eingabe erfolgreich</br>'):print_r("Eingabe fehlgeschlagen!!!</br>"); 
        echo("-----");
        echo "-----";
    }
}else{
    echo "-----";
    echo("0");
    echo("-----");
    echo("-----");
    echo("-----");
}
echo "</br>";

last_3_entries($conn);



?>