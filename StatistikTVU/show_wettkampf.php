<?php
include_once("inc/connect.inc.php"); 
if ($conn->connect_error) {echo "Connection ERROR</br>";}
include_once("inc/class_sql.inc.php"); 
//------------------ Kategorie aus Eingabe speichern ---------------
$sql_wettkampf = new sql_list_performance_wettkampf($_POST['postwettkampf'], 'b.ID, Leistung, Disziplin, Vorname, Name, Ort, Datum', 'bestenliste b', 'b.ID DESC');
$sql_wettkampf->add_statement_INNER_JOIN("mitglied m", "b.Mitglied = m.ID");
$sql_wettkampf->add_statement_INNER_JOIN("wettkampf w","b.Wettkampf = w.ID");
$sql_wettkampf->add_statement_INNER_JOIN("disziplin d","b.DisziplinID = d.ID");
$result=$conn->query($sql_wettkampf->get_sql());
$array_result = $result->fetch_all(MYSQLI_ASSOC);
foreach ($array_result as $key => $performance) {
    echo "<p>ID: ".$performance['ID'].", ";
    echo $performance['Leistung'].", ".$performance['Disziplin'].", ".$performance['Vorname']." ".$performance['Name'].", ". $performance['Ort']."</p>";
}

?>
